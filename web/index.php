<?php
/**
 * Set $SITE_PATH so it's available for setting up the CMS
 */
$SITE_PATH = realpath(__DIR__ . '/..');

/**
 * Almost all autoloading is handled by Composer
 */
require $SITE_PATH . '/vendor/autoload.php';

/**
 * Set any additional paths. The preferred method for setting paths is in
 * config files, but it can be done here. Settings here will override any
 * paths set in any config files.
 */
$PATHS = [];

/**
 * Set notifications here if you need to notify the user of anything, but
 * the message is being generated outside the CMS, or before it is initialized.
 */
$NOTICES = [];
$WARNINGS = [];
$ERRORS = [];

/**
 * Code added here will run before CMS setup begins
 */

# BEGIN DIGRAPH-MANAGED: INDEX-SETUP
# Do not edit this code, it will be replaced whenever composer update/install runs
# digraph-project-core/files/index-setup.php => web/index.php

# set up new config file to get started
$config = new \Flatrr\Config\Config();

# load digraph-project-core default config
$config->readFile(\DigraphProject\ScriptHandler::configFile());

# set site path
$config['paths.site'] = $SITE_PATH;

# load site config, overwriting anything else set
$config->readFile($SITE_PATH . '/digraph.yaml', null, true);

# load environment config, overwriting anything else set
if (file_exists($SITE_PATH . '/env.yaml')) {
    $config->readFile($SITE_PATH . '/env.yaml', null, true);
}

# override config paths using array from index.php
$config->merge($PATHS, 'paths', true);

# set cache path to system temp as a fallback, because we NEED a cache
if (!$config['paths.cache'] || !is_writeable($config['paths.cache'])) {
    $WARNINGS[] = 'Cache directory is not set or not writeable. Falling back to path in sys_get_temp_dir()';
    $config['paths.cache'] = sys_get_temp_dir() . '/digraph-cache';
    if (!is_writeable($config['paths.cache'])) {
        $ERRORS[] = 'Cache directory is not writeable. Site may not behave correctly.';
    }
}

# try to guess URL if necessary
if (!$config['url']) {
    $config['url'] = [
        'protocol' => '//',
        'domain' => $_SERVER['HTTP_HOST'],
        'path' => preg_replace('/index\.php$/', '', $_SERVER['SCRIPT_NAME']),
        'forcehttps' => false,
    ];
}

# set up CMS using Bootstrapper
# everything the bootstrapper does can be done manually, but
# in most cases it's better to use it
$cms = \Digraph\Bootstrapper::bootstrap($config);

# load site config, overwriting anything else set, done twice to override modules
$config->readFile($SITE_PATH . '/digraph.yaml', null, true);

# load environment config, overwriting anything else set, done twice to override modules
if (file_exists($SITE_PATH . '/env.yaml')) {
    $config->readFile($SITE_PATH . '/env.yaml', null, true);
}

# override config paths using array from index.php, done twice to override modules
$config->merge($PATHS, 'paths', true);

# set up new request/response package
# it's advisable to use the Bootstrapper url() method for
# getting your query string
$package = new Digraph\Mungers\Package([
    'request.url' => \Digraph\Bootstrapper::url(),
    'request.post' => $_POST,
]);

# END DIGRAPH-MANAGED: INDEX-SETUP

/**
 * Code added here will run after the CMS and request/response package are
 * built, but before the request is actually processed.
 */

# BEGIN DIGRAPH-MANAGED: INDEX-EXECUTE
# Do not edit this code, it will be replaced whenever composer update/install runs
# digraph-project-core/files/index-execute.php => web/index.php

# send notifications
foreach ($NOTICES as $message) {
    $cms->helper('notifications')->notice($message);
}
foreach ($WARNINGS as $message) {
    $cms->helper('notifications')->warning($message);
}
foreach ($ERRORS as $message) {
    $cms->helper('notifications')->error($message);
}

# calling CMS::fullMunge() will apply the mungers specified
# in the "fullmunge" config
# by default this means building a response and also rendering it
$cms->fullMunge($package);

# END DIGRAPH-MANAGED: INDEX-EXECUTE

/**
 * Code added here will execute after a response is completed and output.
 */

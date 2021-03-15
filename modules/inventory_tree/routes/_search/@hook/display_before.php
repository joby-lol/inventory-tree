<?php
// redirect to item if query is a valid tag
$item = $cms->helper('inventory')->item($package['url.args.search_q']);
if ($item) {
    $cms->helper('notifications')->flashConfirmation('Item located');
    $package->redirect($item->url());
}

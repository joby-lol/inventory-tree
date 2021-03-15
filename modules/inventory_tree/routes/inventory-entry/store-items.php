<?php
use Digraph\Modules\inventory_tree\TagSearchForm;

$package->cache_noStore();
$helper = $cms->helper('inventory');
$notifications = $cms->helper('notifications');
$container = $package->noun();

$form = new TagSearchForm('Store items in this container');
if ($form->handle()) {
    $tag = $form['tag']->value();
    if ($item = $helper->item($tag)) {
        $item->setContainer($container['dso.id']);
        $notifications->flashConfirmation('Item location set');
        $package->redirect($package->url());
    } else {
        $notifications->flashError('Tag not found');
        $package->redirect($package->url());
    }
    return;
}

echo $form;

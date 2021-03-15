<?php
use Digraph\Modules\inventory_tree\TagSearchForm;

$package->cache_noStore();
$helper = $cms->helper('inventory');
$notifications = $cms->helper('notifications');
$item = $package->noun();

$form = new TagSearchForm('Set item location');
if ($form->handle()) {
    $tag = $form['tag']->value();
    if ($parent = $helper->item($tag)) {
        $item->setContainer($parent['dso.id']);
        $notifications->flashConfirmation('Item location set');
        $package->redirect($package->url());
    } else {
        $notifications->flashError('Tag not found');
        $package->redirect($package->url());
    }
    return;
}

echo $form;

<?php
use Digraph\Modules\inventory_tree\TagSearchForm;

$package->cache_noStore();
$helper = $cms->helper('inventory');
$notifications = $cms->helper('notifications');
$item = $package->noun();

$form = new TagSearchForm('Add tag to item');
if ($form->handle()) {
    $tag = $form['tag']->value();
    if ($helper->item($tag)) {
        $notifications->flashError('Tag already associated, please try a different one');
        $package->redirect($package->url());
    } else {
        $helper->setTag($tag, $item['dso.id']);
        $notifications->flashConfirmation('Tag associated with item');
        $package->redirect($item->url());
    }
    return;
}

echo $form;

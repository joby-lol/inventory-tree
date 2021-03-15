<?php
namespace Digraph\Modules\inventory_tree;

use Digraph\DSO\Noun;
use Digraph\Forms\Fields\FileStoreFieldMulti;

class InventoryEntry extends Noun
{
    const FILESTORE = true;

    public function setContainer(string $parent)
    {
        /** @var \Digraph\Graph\EdgeHelper */
        $edges = $this->cms()->helper('edges');
        $edges->deleteParents($this['dso.id'], 'inventory-contains');
        $edges->create($parent, $this['dso.id'], 'inventory-contains');
    }

    public function actions($links)
    {
        $links = parent::actions($links);
        $links['set-location'] = '!id/set-location';
        $links['store-items'] = '!id/store-items';
        $links['add-tag'] = '!id/add-tag';
        return $links;
    }

    public function files(): array
    {
        return $this->cms()->helper('filestore')->list(
            $this,
            'files'
        );
    }

    public function helper(): InventoryHelper
    {
        return $this->cms()->helper('inventory');
    }

    public function name($verb = null)
    {
        return parent::name($verb) . ($this['category'] ? ' (' . $this['category'] . ')' : '');
    }

    public function inventory(): array
    {
        return $this->cms()->helper('graph')->children($this['dso.id'], 'inventory-contains');
    }

    public function formMap(string $action): array
    {
        $map = parent::formMap($action);
        $map['digraph_title'] = false;
        $map['digraph_body']['label'] = 'Description/notes';
        $map['digraph_body']['class'] = 'digraph_content_default';
        $map['inv_category'] = [
            'label' => 'Item category',
            'field' => 'category',
            'class' => 'fieldvalue',
            'extraConstructArgs' => [
                ['inventory-entry'],
                ['category'],
                true,
            ],
            'weight' => 200,
        ];
        // $map['inv_status'] = [
        //     'label' => 'Item status',
        //     'field' => 'status',
        //     'class' => 'fieldvalue',
        //     'default' => 'normal',
        //     'extraConstructArgs' => [
        //         ['inventory-entry'],
        //         ['status'],
        //         true,
        //     ],
        //     'weight' => 200,
        // ];
        $map['files'] = [
            'weight' => 550,
            'label' => 'Related files, manuals, etc.',
            'class' => FileStoreFieldMulti::class,
            'extraConstructArgs' => ['files'],
        ];
        return $map;
    }

    public function parentEdgeType(Noun $parent)
    {
        if ($parent instanceof InventoryEntry) {
            return 'inventory-contains';
        }
        return null;
    }
}

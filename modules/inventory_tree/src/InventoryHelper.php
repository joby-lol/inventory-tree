<?php
namespace Digraph\Modules\inventory_tree;

use Digraph\Helpers\AbstractHelper;
use mofodojodino\ProfanityFilter\Check;

class InventoryHelper extends AbstractHelper
{
    /** @var \Digraph\Data\DatastoreNamespace */
    protected $datastore;

    const TAG_LENGTH = 8;
    const TAG_CHARS = 'abcdefghijkmnorstuvwxyz0123456789';
    const TAG_COLORS = [
        '0' => 'F44336',
        '1' => 'E91E63',
        '2' => '9C27B0',
        '3' => '673AB7',
        '4' => '3F51B5',
        '5' => '2196F3',
        '6' => '03A9F4',
        '7' => '00BCD4',
        '8' => '009688',
        '9' => '4CAF50',
        'a' => '8BC34A',
        'b' => 'CDDC39',
        'c' => 'FFEB3B',
        'd' => 'FFC107',
        'e' => 'FF9800',
        'f' => 'FF5722',
    ];

    public function generateTag(): string
    {
        $id = '';
        while (strlen($id) < static::TAG_LENGTH) {
            $id .= substr(
                static::TAG_CHARS,
                rand(0, strlen(static::TAG_CHARS) - 1),
                1
            );
        }
        // tag can't have profanity
        $check = new Check();
        if ($check->hasProfanity($id)) {
            $id = $this->generateTag();
        }
        // tag can't already exist
        if ($this->item($id)) {
            $id = $this->generateTag();
        }
        // return valid tag
        return $id;
    }

    public function construct()
    {
        $this->datastore = $this->cms->helper('datastore')->namespace('inventory-tree');
    }

    public function color(string $tag): string
    {
        return '#' . static::TAG_COLORS[substr(md5($tag), 0, 1)];
    }

    public function setTag(string $tag, string $item)
    {
        $this->datastore->set($tag, $item);
    }

    public function tags(string $item)
    {
        return $this->datastore->query($item);
    }

    public function item(string $tag): ?InventoryEntry
    {
        if ($item = $this->datastore->get($tag)) {
            return $this->cms->read($item, false);
        }
        return null;
    }

}

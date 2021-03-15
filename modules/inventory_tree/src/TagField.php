<?php
namespace Digraph\Modules\inventory_tree;

use Formward\FieldInterface;
use Formward\Fields\Input;

class TagField extends Input
{
    public function __construct(string $label, string $name=null, FieldInterface $parent=null)
    {
        parent::__construct($label, $name, $parent);
        $this->addClass('inventory-tag-field');
    }
}

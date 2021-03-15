<?php
namespace Digraph\Modules\inventory_tree;

use Digraph\Forms\Form;
use Formward\FieldInterface;

class TagSearchForm extends Form
{
    public function __construct(string $label, string $name=null, FieldInterface $parent=null)
    {
        parent::__construct($label, $name, $parent);
        $this->attr('enctype', 'multipart/form-data');
        $this->addClass('inventory-tag-form');
        $this['tag'] = new TagField('Enter or scan tag');
    }
}

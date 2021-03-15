<?php
namespace Digraph\Modules\core_taxonomy;

use Digraph\DSO\Noun;

class TaxonomyTag extends Noun
{
    public function title($verb = null)
    {
        return $this['category'] . ': ' . parent::title($verb);
    }

    public function name($verb = null)
    {
        return $this['category'] . ': ' . parent::name($verb);
    }

    public function tagName()
    {
        return parent::name();
    }

    public function tagCategory()
    {
        return $this['category'];
    }

    public function formMap(string $action): array
    {
        $map = parent::formMap($action);
        $map['taxonomy_group'] = [
            'label' => 'Taxonomy category',
            'field' => 'category',
            'class' => 'fieldvalue',
            'extraConstructArgs' => [
                ['taxonomy-tag'],
                ['category'],
                true,
            ],
            'weight' => 200,
            'required' => true,
        ];
        return $map;
    }

    public function childEdgeType(Noun $parent)
    {
        return 'taxonomy-tag';
    }
}

<?php namespace Indikator\Paste\Components;

use Cms\Classes\ComponentBase;
use Indikator\Paste\Models\Block;

class Blocks extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'indikator.paste::lang.component.block_name',
            'description' => 'indikator.paste::lang.component.block_description'
        ];
    }

    public function onRun()
    {
        $this->page['blocks'] = Block::where('status', 1)->orderBy('sort_order', 'asc')->get()->all();
    }
}

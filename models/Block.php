<?php namespace Indikator\Paste\Models;

use Model;

class Block extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    protected $table = 'indikator_paste_block';

    public $rules = [
        'name'   => 'required',
        'code'   => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:indikator_paste_block'],
        'status' => 'required|between:1,2|numeric'
    ];

    public $translatable = [
        'title',
        'subtitle',
        'content',
        'button_name',
        'button_link'
    ];

    public function getCategoryOptions()
    {
        $result = [0 => 'indikator.paste::lang.form.none'];
        $items = BlockCategory::orderBy('name', 'asc')->get()->all();

        foreach ($items as $item) {
            $result[$item->id] = $item->name;
        }

        return $result;
    }
}

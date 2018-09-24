<?php namespace Indikator\Paste\Models;

use Model;

class Text extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    protected $table = 'indikator_paste_text';

    public $rules = [
        'name'   => 'required',
        'code'   => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:indikator_paste_text'],
        'type'   => 'required|between:1,3|numeric',
        'status' => 'required|between:1,2|numeric'
    ];

    public $translatable = [
        'content'
    ];
}

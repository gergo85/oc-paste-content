<?php namespace Indikator\Paste\Models;

use Model;

class Text extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $table = 'indikator_paste_text';

    public $rules = [
        'code'   => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:indikator_paste_text'],
        'status' => 'required|between:1,2|numeric'
    ];
}

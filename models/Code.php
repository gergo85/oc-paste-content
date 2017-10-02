<?php namespace Indikator\Paste\Models;

use Model;

class Code extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $table = 'indikator_paste_code';

    public $rules = [
        'name'   => 'required',
        'code'   => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:indikator_paste_code'],
        'type'   => 'required|between:1,3|numeric',
        'status' => 'required|between:1,2|numeric'
    ];
}

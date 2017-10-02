<?php namespace Indikator\Paste\Models;

use Model;

class BlockCategory extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $table = 'indikator_paste_block_category';

    public $rules = [
        'name' => 'required',
        'code' => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:indikator_paste_block_category']
    ];
}

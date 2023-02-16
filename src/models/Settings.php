<?php

namespace digilab\split\models;

use craft\base\Model;

class Settings extends Model
{
    public $fields = [];

    public function rules()
    {
        return [
            [['fields'], 'required'],
            // ...
        ];
    }
}

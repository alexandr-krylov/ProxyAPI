<?php

namespace app\models;

use yii\db\ActiveRecord;

class Transaction extends ActiveRecord
{
    public static function tableName()
    {
        return '{{transaction}}';
    }
    public function rules()
    {
        return [
            [['source', 'destination', 'currency', 'value'], 'required'],
            [['source', 'destination'], 'integer'],
            ['currency', 'string'],
            ['value', 'number'],
            ['id', 'safe']
        ];
    }
}

<?php

namespace app\models;

use yii\db\ActiveRecord;

class Wallet extends ActiveRecord
{
    public static function tableName()
    {
        return '{{wallet}}';
    }
    public function rules()
    {
        return [
            [['owner_id', 'currency', 'type'], 'required'],
            [['owner_id', 'currency', ], 'string'],
            ['value', 'number'],
        ];
    }
}

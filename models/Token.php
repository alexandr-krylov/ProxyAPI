<?php

namespace app\models;

use yii\db\ActiveRecord;

class Token extends ActiveRecord
{
    public static function tableName()
    {
        return '{{token}}';
    }
    public function rules()
    {
        return [
            [['ticker', 'price', 'quantity', 'maturity_date', 'debitor', 'category'], 'required'],
            ['ticker', 'unique'],
            ['id', 'safe'],
        ];
    }
}
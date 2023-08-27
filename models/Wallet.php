<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\enums\Type;

class Wallet extends ActiveRecord
{
    public $typeText;

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
    public function fields()
    {
        $fields = parent::fields();
        $fields = $fields + ['typeText' => 'typeText'];
        return $fields;
    }
}

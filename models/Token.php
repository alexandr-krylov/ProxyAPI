<?php

namespace app\models;

use DateTime;
use yii\db\ActiveRecord;

class Token extends ActiveRecord
{
    public $ask;
    public $bid;
    public $profitability;
    private ?int $_days = null;
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
    public function fields()
    {
        $fields = parent::fields();
        $fields = $fields + [
            'ask' => 'ask',
            'bid' => 'bid',
            'profitability' => 'profitability',
            'days' => 'days',
        ];
        return $fields;
    }
    public function setDays($days)
    {
        $this->_days = $days;
    }
    /**
     * days to cancellation = date cancellation - today
     */
    public function getDays()
    {
        if ($this->_days === null)
        {
            $this->setDays(
                (int)(new DateTime())->diff(new DateTime($this->maturity_date))->format('%r%a')
            );
        }
        return $this->_days;
    }
}

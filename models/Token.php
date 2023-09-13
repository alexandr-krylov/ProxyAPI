<?php

namespace app\models;

use DateTime;
use yii\db\ActiveRecord;
use app\services\HttpClient;

class Token extends ActiveRecord
{
    private ?float $_ask = null;
    private ?float $_bid = null;
    private ?float $_profitability = null;
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
        $this->_days = (int)$days;
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
    
    public function setBid($bid)
    {
        $this->_bid = (float)$bid;
    }

    public function getBid()
    {
        if ($this->_bid === null)
        {
            $this->_setAskBid();
        }
        return $this->_bid;
    }
    public function setAsk($ask)
    {
        $this->_ask = (float)$ask;
    }
    public function getAsk()
    {
        if ($this->_ask === null)
        {
            $this->_setAskBid();
        }
        return $this->_ask;
    }
    private function _setAskBid()
    {
        $client = new HttpClient();
        $responce = $client->getMarketData(['ticker' => $this->ticker]);
        $this->setBid($responce['bid']);
        $this->setAsk($responce['ask']);
    }
    public function setProfitability($profitability)
    {
        $this->_profitability = (float)$profitability;
    }

    public function getProfitability()
    {
        if ($this->_profitability === null)
        {
            if ($this->ask == 0 or $this->days == 0)
            {
                $this->setProfitability(0);
            } else 
            {
                $this->setProfitability(
                    round((
                        (($this->price - $this->ask) / $this->ask)
                        * (360 / $this->days)
                    ) * 100, 2)
                );
            }
        }
        return $this->_profitability;
    }
}

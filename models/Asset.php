<?php

namespace app\models;

use Yii;

class Asset extends Wallet
{
    private $_maturityDate = null;
    private ?float $_nominal = null;
    private ?float $_maturityValue = null;
    private ?float $_currentPrice = null;
    private ?float $_currentValue = null;

    public function fields()
    {
        $fields = parent::fields();
        $fields = $fields + [
            'maturity_date' => 'maturityDate',
            'current_price' => 'currentPrice',
            'nominal' => 'nominal',
            'current_value' => 'currentValue',
            'maturity_value' => 'maturityValue',
        ];
        return $fields;
    }
    public function getMaturityDate()
    {
        if (is_null($this->_maturityDate))
        {
            $this->setTokenFields();
        }
        return $this->_maturityDate;
    }
    public function setMaturityDate($maturityDate)
    {
        $this->_maturityDate = $maturityDate;
    }
    public function getNominal()
    {
        if (is_null($this->_nominal))
        {
            $this->setTokenFields();
        }
        return $this->_nominal;
    }
    public function setNominal($nominal)
    {
        $this->_nominal = is_null($nominal) ? null : round($nominal, 2);
    }
    private function setTokenFields()
    {
        $token = Token::findOne(['ticker' => $this->currency]);
        $this->setMaturityDate($token?->maturity_date);
        $this->setNominal($token?->price);
        $this->setCurrentPrice($token?->bid);
    }
    public function getMaturityValue()
    {
        if (is_null($this->_maturityValue))
        {
            if ($this->currency == Yii::$app->params['mainCurrency'])
            {
                $this->setMaturityValue($this->value);
            } else 
            {
                $this->setMaturityValue($this->value * $this->getNominal());
            }
        }
        return $this->_maturityValue;
    }
    public function setMaturityValue($maturityValue)
    {
        $this->_maturityValue = is_null($maturityValue) ? null : round($maturityValue, 2);
    }
    public function getCurrentPrice()
    {
        if (is_null($this->_currentPrice))
        {
            $this->setTokenFields();
        }
        return $this->_currentPrice;
    }
    public function setCurrentPrice($currentPrice)
    {
        $this->_currentPrice = is_null($currentPrice) ? null : round($currentPrice, 2);
    }
    public function getCurrentValue()
    {
        if (is_null($this->_currentValue))
        {
            if ($this->currency == Yii::$app->params['mainCurrency'])
            {
                $currentValue = $this->value;
            } else
            {
                $currentValue = $this->value * $this->getCurrentPrice();
            }
            $this->setCurrentValue($currentValue);
        }
        return $this->_currentValue;
    }
    public function setCurrentValue($currentValue)
    {
        $this->_currentValue = is_null($currentValue) ? null : round((float)$currentValue, 2);
    }
}

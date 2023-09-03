<?php

namespace app\services;

use app\models\Token;
use app\models\Wallet;
use app\enums\TickerStatus;
use app\enums\Type;
use app\services\Transaction;
use app\services\HttpClient;
use DateTime;
use Yii;


class Redemption
{
    protected $user;
    protected $currency;
    protected $ticker;
    protected $price;
    protected $tickers;
    protected $transactionService;
    public function __construct()
    {
        $this->user = Yii::$app->params['RedemptionUser'];
        $this->currency = Yii::$app->params['RedemptionCurrency'];
        $this->getTickerForRedemption();
        $this->transactionService = new Transaction();
    }
    public function up()
    {
        foreach ($this->tickers as $ticker)
        {
            $this->ticker = $ticker->ticker;
            $this->price = (float)$ticker->price;
            $this->redemptionWallets();
            $this->cleanDOM();
            $ticker->status = TickerStatus::Redempted->value;
            $ticker->save();
        }
    }
    private function getTickerForRedemption()
    {
        $query = Token::find();
        $query->where(['status' => TickerStatus::Active->value]);
        $query->andWhere(['<', 'maturity_date', (new DateTime())->format(DATE_ATOM)]);
        $this->tickers = $query->all();
    }
    private function redemptionWallets()
    {
        $query = Wallet::find();
        $query->where(['currency' => $this->ticker]);
        $query->andWhere(['>', 'value', 0]);
        $query->andWhere(['<>', 'owner_id', $this->user]);
        $tickerWalletQuery = Wallet::find();
        $tickerWalletQuery->where(['currency' => $this->ticker, 'owner_id' => $this->user, 'type' => Type::Main->value]);
        $thickerWallet = $tickerWalletQuery->one();
        $currencyWalletQuery = Wallet::find();
        $currencyWalletQuery->where(['currency' => $this->currency, 'owner_id' => $this->user, 'type' => Type::Main->value]);
        $currencyWallet = $currencyWalletQuery->one();
        foreach($query->all() as $walletForRedemption)
        {
            $tickerQTY = $walletForRedemption->value;
            //ticker from user
            $this->transactionService->create([
                'source' => $walletForRedemption->id,
                'destination' => $thickerWallet->id,
                'currency' => $this->ticker,
                'value' => $tickerQTY,
            ]);
            //money to user
            $this->transactionService->create([
                'source' => $currencyWallet->id,
                'destination' => Wallet::findOne([
                    'owner_id' => $walletForRedemption->owner_id,
                    'currency' => $this->currency,
                    'type' => Type::Main->value,
                ])->id,
                'currency' => $this->currency,
                'value' => $tickerQTY * $this->price,
            ]);
        }
    }
    private function cleanDOM()
    {
        (new HttpClient())->setOrdersRedempted(['ticker' => $this->ticker]);
    }
}

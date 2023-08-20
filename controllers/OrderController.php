<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\services\HttpClient;
use app\models\Wallet;
use app\enums\Type;
use app\services\Transaction;

class OrderController extends Controller
{
    public function actionCreate()
    {
        //1. CHECK THE WALLET
        if ($this->request->post('type') == 'limit')
        {
            if ($this->request->post('side') == 'buy')
            {
                $wallet = Wallet::findOne(
                    [
                        'owner_id' => $this->request->post('owner_id'),
                        'currency' => Yii::$app->params['mainCurrency'],
                    ]
                );
                if ($wallet->value >= $this->request->post('quantity') * $this->request->post('price'))
                {
                    $this->hold(
                        $this->request->post('owner_id'),
                        Yii::$app->params['mainCurrency'],
                        $this->request->post('quantity') * $this->request->post('price')
                    );
                }
            }
            if ($this->request->post('side') == 'sell')
            {
                $wallet = Wallet::findOne(
                    [
                        'owner_id' => $this->request->post('owner_id'),
                        'currency' => $this->request->post('ticker'),
                    ]
                );
                if ($wallet->value >= $this->request->post('quantity'))
                {
                    $this->hold(
                        $this->request->post('owner_id'),
                        $this->request->post('ticker'),
                        $this->request->post('quantity')
                    );
                }
            }
        }
        //2. CREATE ORDER
        $client = new HttpClient();
        $responses =  $client->send($this->request->post());
        if ($this->request->post('type') == 'limit')
        {
            return $responses;
        }
        //3. REALISE WALLET
        $result = [];
        foreach ($responses as $response)
        {
            if ($this->request->post('side') == 'buy')
            {
                //['owner_id' => $order1->owner_id, 'price' => $order1->price, 'quantity' => $quantity]
                $currency1 = Yii::$app->params['mainCurrency'];
                $source1 = Wallet::findOne(
                    [
                        'currency' => $currency1,
                        'owner_id' => $this->request->post('owner_id'),
                        'type' => Type::Main->value,
                    ]
                )->id;
                $destination1 = Wallet::findOne(
                    [
                        'currency' => $currency1,
                        'owner_id' => $response['owner_id'],
                        'type' => Type::Main->value,
                    ]
                )->id;
                $value1 = $response['price'] * $response['quantity'];

                $currency2 = $this->request->post('ticker');
                $source2 = Wallet::findOne(
                    [
                        'currency' => $currency2,
                        'owner_id' => $response['owner_id'],
                        'type' => Type::Hold->value,
                    ]
                )->id;
                $destination2 = Wallet::findOne(
                    [
                        'currency' => $currency2,
                        'owner_id' => $this->request->post('owner_id'),
                        'type' => Type::Main->value,
                    ]
                )->id;
                $value2 = $response['quantity'];
            }
            if ($this->request->post('side') == 'sell')
            {
                $currency1 = $this->request->post('ticker');
                $source1 = Wallet::findOne(
                    [
                        'currency' => $currency1,
                        'owner_id' => $this->request->post('owner_id'),
                        'type' => Type::Main->value,
                    ]
                )->id;
                $destination1 = Wallet::findOne(
                    [
                        'currency' => $currency1,
                        'owner_id' => $response['owner_id'],
                        'type' => Type::Main->value,
                    ]
                )->id;
                $value1 = $response['quantity'];

                $currency2 = Yii::$app->params['mainCurrency'];
                $source2 = Wallet::findOne(
                    [
                        'currency' => $currency2,
                        'owner_id' => $response['owner_id'],
                        'type' => Type::Hold->value,
                    ]
                )->id;
                $destination2 = Wallet::findOne(
                    [
                        'currency' => $currency2,
                        'owner_id' => $this->request->post('owner_id'),
                        'type' => Type::Main->value,
                    ]
                )->id;
                $value2 = $response['price'] * $response['quantity'];
            }
            $result[] = (new Transaction)->create(
                [
                    'source' => $source1,
                    'destination' => $destination1,
                    'currency' => $currency1,
                    'value' => $value1
                ]
            );
            $result[] = (new Transaction)->create(
                [
                    'source' => $source2,
                    'destination' => $destination2,
                    'currency' => $currency2,
                    'value' => $value2
                ]
            );
        }
        return $result;
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
    private function hold($user, $currency, $amount)
    {
        $sourceWallet = Wallet::findOne(
            [
                'owner_id' => $user,
                'currency' => $currency,
                'type' => Type::Main->value,
            ]
        );
        $destinationWallet = Wallet::findOne(
            [
                'owner_id' => $user,
                'currency' => $currency,
                'type' => Type::Hold->value,
            ]
        );
        return (new Transaction())->create(
            [
                'source' => $sourceWallet->id,
                'destination' => $destinationWallet->id,
                'currency' => $currency,
                'value' => $amount,
            ]
        );
    }
}

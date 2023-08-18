<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Transaction;
use app\models\Wallet;
use Exception;
use Throwable;

class TransactionController extends Controller
{
    public function actionCreate()
    {
        $transaction = new Transaction();
        $transaction->attributes = $this->request->post();
        $dbTransaction = Transaction::getDb()->beginTransaction();
        try {
            $sourceWallet = Wallet::findOne($transaction->source);
            $destinationWallet = Wallet::findOne($transaction->destination);
            if ($sourceWallet->value - $transaction->value < 0) throw new Exception("Source wallet no enough value");
            if ($sourceWallet->currency != $transaction->currency) throw new Exception("No match currecies");
            if ($destinationWallet->currency != $transaction->currency) throw new Exception("No match currencies");
            $sourceWallet->value = $sourceWallet->value - $transaction->value;
            $destinationWallet->value = $destinationWallet->value + $transaction->value;
            $sourceWallet->save();
            $destinationWallet->save();
            $transaction->save();
            $dbTransaction->commit();
            return true;
        } catch (Throwable $e){
            $dbTransaction->rollBack();
            $this->response->statusCode = 400;
            return $e->getMessage();
        }
    }
    public function actionView()
    {
        return Transaction::find()
            ->leftJoin('wallet', 'wallet.id = transaction.source OR wallet.id = transaction.destination')
            ->where(['wallet.owner_id' => $this->request->get('owner_id')])
            ->all();
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}

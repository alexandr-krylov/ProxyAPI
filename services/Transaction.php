<?php

namespace app\services;

use app\models\Transaction as TransactionModel;
use app\models\Wallet;
use Exception;
use Throwable;

class Transaction
{
    public function create($request)
    {
        $transaction = new TransactionModel();
        $transaction->attributes = $request;
        $dbTransaction = TransactionModel::getDb()->beginTransaction();
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
            return $e->getMessage();
        }
    }
}

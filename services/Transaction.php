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
            if ($sourceWallet->id == $destinationWallet->id) throw new Exception("Transaction between the same wallets");
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
    public function getMine($request)
    {
        $query = TransactionModel::find();
        $query->select([
                'transaction.id',
                'transaction.source',
                'transaction.destination',
                'transaction.currency',
                'ROUND(transaction.value, 2) AS value',
                'transaction.created_at'
            ]);
        $query->leftJoin('wallet AS sourceWallet', 'sourceWallet.id = transaction.source')
            ->leftJoin('wallet AS destinationWallet', 'destinationWallet.id = transaction.destination')
            ->orWhere([
                'and',
                ['sourceWallet.owner_id' => $request['owner_id']],
                ['!=', 'destinationWallet.owner_id', $request['owner_id']]
                ])
            ->orWhere([
                'and',
                ['!=', 'sourceWallet.owner_id', $request['owner_id']],
                ['destinationWallet.owner_id' => $request['owner_id']]
            ]);
        if (key_exists('date', $request))
        {
            $query->andWhere("DATE(transaction.created_at) = '{$request['date']}'");
        }
        $query->orderBy(['transaction.created_at' => SORT_DESC]);
        $result = $query->all();
        return $result;
    }
}

<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Transaction;
use app\models\Wallet;
use Exception;
use Throwable;
use app\services\Transaction as TransactionService;

class TransactionController extends Controller
{
    public function actionCreate()
    {
        try {
        return (new TransactionService())->create($this->request->post());
        } catch (Throwable $e) {
            $this->response->statusCode = 400;
            return $e->getMessage();
        }
    }
    public function actionView()
    {
        return Transaction::find()
            ->select([
                '*',
                'ROUND(transaction.value, 2) AS value',
            ])
            ->leftJoin('wallet AS sourceWallet', 'sourceWallet.id = transaction.source')
            ->leftJoin('wallet AS destinationWallet', 'destinationWallet.id = transaction.destination')
            ->orWhere([
                'and',
                ['sourceWallet.owner_id' => $this->request->get('owner_id')],
                ['!=', 'destinationWallet.owner_id', $this->request->get('owner_id')]
                ])
            ->orWhere([
                'and',
                ['!=', 'sourceWallet.owner_id', $this->request->get('owner_id')],
                ['destinationWallet.owner_id' => $this->request->get('owner_id')]
                ])
            ->all();
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}

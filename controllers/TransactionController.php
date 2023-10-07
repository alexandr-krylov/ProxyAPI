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
        return (new TransactionService())->getMine($this->request->get());
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}

<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Wallet;
use app\enums\Type;

class WalletController extends Controller
{
    public function actionCreate()
    {
        $wallet = new Wallet();
        $wallet->attributes = $this->request->post();
        $wallet->type = 
            match ($wallet->type) {'main' => Type::Main, 'hold' => Type::Hold,};
        return $wallet->save();
    }
    public function actionView()
    {
        return Wallet::findAll(['owner_id' => $this->request->get('owner_id')]);
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}

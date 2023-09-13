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
        $query = Wallet::find();

        $query->select([
            'id',
            'currency',
            'ROUND(value, 2) AS value',
            'type',
            'created_at',
            'updated_at',
            '(CASE type WHEN 1 THEN \'' . ((array)Type::from(1))['name'] . '\' WHEN 2 THEN \'' . ((array)Type::from(2))['name'] . '\' END) AS typeText'
            ]);
        $query->where(['owner_id' => $this->request->get('owner_id')]);
        $query->andWhere(['<>', 'value', 0]);
        $query->orderBy('currency', 'type');
        return $query->all();
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}

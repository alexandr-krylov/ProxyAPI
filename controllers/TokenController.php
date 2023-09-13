<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Token;

class TokenController extends Controller
{
    public function actionCreate()
    {
        $token = new Token();
        $token->attributes = $this->request->post();
        return $token->save();
    }

    /**
     * profitability = (((cancellation price - current price) / current price) / days to cancellation) * 365  * 100 % = % per year
     */
    public function actionIndex()
    {
        $query = Token::find();
        if (null !== $this->request->get('ticker'))
        {
            $query->select([
                '*',
                'ROUND(price, 2) AS price',
            ]);
            $query->where(['ticker' => $this->request->get('ticker')]);
        }
        $tokens = $query->all();
        return $tokens;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}

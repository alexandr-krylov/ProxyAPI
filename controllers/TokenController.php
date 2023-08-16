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
        // $token->volume = $token->price * $token->quantity;
        return $token->save();
    }

    public function actionIndex()
    {
        return Token::find()->all();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}

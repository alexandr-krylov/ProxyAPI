<?php

namespace app\controllers;

use yii\rest\Controller;
use app\services\HttpClient;

class OrderController extends Controller
{
    public function actionCreate()
    {
        $client = new HttpClient();
        $response =  $client->send($this->request->post());
        return $response;
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
}

<?php

namespace app\services;

use Yii;
use yii\httpclient\Client;

class HttpClient
{
    public function send($request)
    {
        $client = new Client(['baseUrl' => Yii::$app->params['DOMUrl']]);
        $response = $client->post('order', $request)->send();
        if ($response->isOk) {
            return $response->data;
        }
    }
}

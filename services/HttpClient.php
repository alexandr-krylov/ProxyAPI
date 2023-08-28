<?php

namespace app\services;

use Yii;
use yii\httpclient\Client;

class HttpClient
{
    private $client;
    public function __construct()
    {
        $this->client = new Client(['baseUrl' => Yii::$app->params['DOMUrl']]);
    }
    public function send($request)
    {
        $response = $this->client->post('order', $request)->send();
        if ($response->isOk) {
            return $response->data;
        }
    }
    public function getMarketData($request)
    {
        $response = $this->client->get('tickerdata', $request)->send();
        if ($response->isOk) {
            return $response->data;
        }
    }
}

<?php

namespace app\services;

use Exception;
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
        $response = $this->client->get('marketdata', $request)->send();
        if ($response->isOk) {
            return $response->data;
        }
    }
    public function setOrdersRedempted($request)
    {
        $response = $this->client->put('order', $request)->send();
        if ($response->isOk)
        {
            return $response->data;
        }
    }
    public function getVolume($request)
    {
        $response = $this->client->get('volume', $request)->send();
        if ($response->isOk)
        {
            return $response->data;
        }
    }
    public function getDOM($request)
    {
        $response = $this->client->get('dom', $request)->send();
        if ($response->isOk)
        {
            return $response->data;
        }
    }
    public function putOrderCancel($request)
    {
        $response = $this->client->delete('order', $request)->send();
        if ($response->isOk)
        {
            return $response->data;
        } else
        {
            throw new Exception($response->data['message']);
        }
    }
}

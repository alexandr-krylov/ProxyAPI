<?php

namespace app\services;

use DateTime;
use app\models\User;
use app\services\Transaction;

class Report
{
    protected HttpClient $client;
    protected DateTime $date;
    protected $report;
    protected User $user;
    public function __construct(User $user, DateTime $date = new DateTime())
    {
        $this->user = $user;
        $this->date = $date;
    }
    public function collect()
    {
        $transactions = $this->getTransactions();
        $orders = $this->getOrders();
        $transactionList = "";
        foreach ($transactions as $transaction)
        {
            $transactionList .=
            "{$transaction->currency}\t" . str_pad($transaction->value, 14) . "\t{$transaction->created_at}\n";
        }
        $orderList = "";
        foreach ($orders as $order)
        {
            $orderList .=
            "{$order->ticker}\t{$order->typeText}\t{$order->sideText}\t{$order->quantity}\t";
        }
        $nowDateTime = (new DateTime())->format(DATE_ATOM);
        $this->report = "Уважаемый участник пилотного проекта \"Факторинг на блокчейне\" \n" .
        "за {$this->date->format("Y-m-d")} с вашего аккаунта были совершены следующие транзакции:\n" .
        $transactionList .
        "на $nowDateTime остаются активными следующие заявки:\n" .
        $orderList .                                         
        "Данный отчет формируется автоматически. чтобы задать вопрос или внести" .
        " предложение используйте чат телеграмма: " .
        "https://t.me/+RGR5eh-4UxtlNmEy";
        return $this;
    }
    protected function getOrders()
    {
        return [];
    }
    protected function getTransactions()
    {
        $result = [];
        $result += (new Transaction())
        ->getMine(['owner_id' => $this->user->uid, 'date' => $this->date->format('Y-m-d')]);
        return $result;
    }
    public function send()
    {

    }
}

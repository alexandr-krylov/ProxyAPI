<?php

namespace app\commands;

use app\enums\UserStatus;
use yii\console\Controller;
use app\services\Report;
use app\models\User;
use DateTime;

class MailingController extends Controller
{
    public $defaultAction = 'send';
    public function actionSend()
    {
        $users = User::findAll(['status' => UserStatus::Active->value]);
        foreach ($users as $user)
        {
            $report = new Report($user, new DateTime('2023-09-03'));
            $report->collect()->send();
        }
        return 0;
    }
}

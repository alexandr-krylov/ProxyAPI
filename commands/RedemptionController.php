<?php

namespace app\commands;

use yii\console\Controller;
use app\services\Redemption;

class RedemptionController extends Controller
{   
    public function actionUp()
    {
        (new Redemption())->up();
        return 0;
    }
}

<?php

namespace app\controllers;

use app\enums\Type;
use app\models\Asset;
use yii\rest\Controller;

class AssetController extends Controller
{
    private $_getParams = null;
    public function getParams()
    {
        if (is_null($this->_getParams))
        {
            $this->_getParams = $this->request->get();
        }
        return $this->_getParams;
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
    public function actionView()
    {
        $query = Asset::find();
        $query->select([
            '*',
            'ROUND(value, 2) AS value',
            '(CASE type WHEN 1 THEN \'' . ((array)Type::from(1))['name'] . '\' WHEN 2 THEN \'' . ((array)Type::from(2))['name'] . '\' END) AS typeText',
        ]);
        $query->where(['owner_id' => $this->getParams()['owner_id']]);
        $query->andWhere(['!=', 'value', 0]);
        $total = ['current_value' => 0.00, 'maturity_value' => 0.00];
        foreach ($query->all() as $asset)
        {
            $total['current_value'] += $asset->currentValue;
            $total['maturity_value'] += $asset->maturityValue;
        }
        return ['assets' => $query->all(), 'total' => $total];
    }
}

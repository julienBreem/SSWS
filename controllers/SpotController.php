<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
class SpotController extends ActiveController
{
    public $modelClass = 'app\models\Spot';
    public function actionSearch()
    {
        if (!empty($_GET)) {
            $model = new $this->modelClass;
            try {
                $provider = new ActiveDataProvider([
                    'query' => $model->find()->where(['like','spot_name',$_GET['query']]),
                    'pagination' => false
                ]);
            } catch (Exception $ex) {
                throw new \yii\web\HttpException(500, 'Internal server error');
            }

            if ($provider->getCount() <= 0) {
                throw new \yii\web\HttpException(404, 'No entries found with this query string');
            } else {
                return $provider;
            }
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }
}

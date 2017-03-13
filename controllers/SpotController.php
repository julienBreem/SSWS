<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
class SpotController extends ActiveController
{
    public $modelClass = 'app\models\Spot';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // remove authentication filter

        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;

        return $behaviors;
    }

    public function actionSearch()
    {
        if (!empty($_GET['query'])) {

            $model = new $this->modelClass;
            $query = $model->find();
            $query->joinWith(['city', 'country']);
            $searchTerm = preg_replace("/[\s,;]+/",'%',$_GET['query']);
            $query->Where(" concat(spot_name,'|',ss_cities.NAME_NO_HTML,'|',ss_countries.country_name) like '%".$searchTerm."%'");
            $query->limit(5);
			//echo $query->createCommand()->rawSql;exit;
            try {
                $provider = new ActiveDataProvider([
                    'query' => $query,
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

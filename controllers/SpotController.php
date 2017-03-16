<?php
namespace app\controllers;

use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;


class SpotController extends Controller
{
    public $modelClass = 'app\models\Spot';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if($_SERVER['REQUEST_METHOD']!='OPTIONS'){
            //echo $_SERVER['REQUEST_METHOD'];exit;
            $behaviors['bearerAuth'] = [
                'class' => HttpBearerAuth::className(),
            ];
        }
        /*
        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['options']

        */
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

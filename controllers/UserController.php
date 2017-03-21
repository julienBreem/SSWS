<?php
namespace app\controllers;

use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;


class UserController extends Controller
{
    public $modelClass = 'app\models\User';
/*
   public function behaviors()
   {

       $behaviors = parent::behaviors();
       if($_SERVER['REQUEST_METHOD']!='OPTIONS'){
           //echo $_SERVER['REQUEST_METHOD'];exit;
           $behaviors['bearerAuth'] = [
               'class' => HttpBearerAuth::className(),
           ];
       }
       return $behaviors;

    }
*/
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

    public function actionLogin()
    {
        $data = json_decode(file_get_contents('php://input'));
        $model = \app\models\User::findIdentityByClientId($data->clientID);
        $model->setAttribute('access_token',$data->access_token);
        if($model->save()){
            return $model;
        } else {
            throw new \yii\web\HttpException(401, 'Bad request');
        }
    }
}

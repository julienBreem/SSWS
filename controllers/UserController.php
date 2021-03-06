<?php
namespace app\controllers;

use app\models\Identity;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class UserController extends Controller
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
            //echo $_SERVER['REQUEST_METHOD'];exit;
            $behaviors['bearerAuth'] = [
                'class' => HttpBearerAuth::className(),
                'except' => ['login', 'create', 'search'],
            ];
        }
        return $behaviors;

    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    public function actionSearch()
    {
        if (!empty($_GET['query'])) {

            $model = new $this->modelClass;
            $query = $model->find();
            $searchTerm = preg_replace("/[\s,;]+/", '%', $_GET['query']);
            $query->Where("name like '%" . $searchTerm . "%'");
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

            return $provider;
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }

    public function actionLogin()
    {
        $data = json_decode(file_get_contents('php://input'));
        $model = Identity::findIdentityByIds($data->identities[0]->provider, $data->identities[0]->user_id)->user;
        $model->setAttribute('access_token', $data->access_token);
        $model->setAttribute('updated_at', $data->updated_at);
        $model->setAttribute('last_ip', Yii::$app->request->getUserIP());
        $model->setAttribute('last_login', date('Y-m-d H:i:s'));
        if ($model->save()) {
            return $model;
        } else {
            throw new HttpException(401, 'Bad request');
        }
    }


    public function actionCreate()
    {
        $data = json_decode(file_get_contents('php://input'));
        $model = new $this->modelClass();
        foreach ($data as $key => $value) {
            if ($model->hasAttribute($key)) $model->setAttribute($key, $value);
        }

        if (!empty($model->picture)) {
            $temp = explode('.', $model->picture);
            $ext = end($temp);
            $temp = explode('?', $ext);
            $cleanExt = reset($temp);
            // generate a unique file name to prevent duplicate filenames
            $picName = Yii::$app->security->generateRandomString() . ".{$cleanExt}";
            // the path to save file, you can set an uploadPath
            // in Yii::$app->params (as used in example below)
            Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/images/';
            $path = Yii::$app->params['uploadPath'] . $picName;
            if (copy($model->picture, $path)) {
                $model->picture = $picName;
            }
        }


        //return $data->identities;
        if ($model->save()) {
            $data->identities[0]->ss_user_id = $model->id_user;
            $identity = new Identity();
            foreach ($data->identities[0] as $key => $value) {
                if ($identity->hasAttribute($key)) $identity->setAttribute($key, $value);
            }
            if ($identity->save()) {
                return $model;
            } else {
                header("HTTP/1.1 400 Bad Request");
                return $identity->getErrors();
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            return $model->getErrors();
        }
    }

    public function actionSpots()
    {
        $userId = Yii::$app->getRequest()->getQueryParam('id');
        if ($userId) {
            $user = \app\models\User::findOne($userId);
            return $user->spots;
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }

    public function actionFollow()
    {

        $followedId = Yii::$app->getRequest()->getQueryParam('id');
        if ($followedId) {
            $followed = \app\models\User::findOne($followedId);
            $user = Yii::$app->user->getIdentity();
            if ($followed->isFollowed()) {
                $user->unlink('followedList', $followed,true);
                return false;
            } else {
                $user->link('followedList', $followed);
                return true;
            }

        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }


    }
}

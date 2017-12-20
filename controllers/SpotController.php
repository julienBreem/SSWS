<?php
namespace app\controllers;

use app\models\Spot;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class SpotController extends Controller
{
    public $modelClass = 'app\models\Spot';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
/*
        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
            //echo $_SERVER['REQUEST_METHOD'];exit;
            $behaviors['bearerAuth'] = [
                'class' => HttpBearerAuth::className(),
            ];
        }
*/
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
            $query->joinWith(['addressComponents', 'country']);
            $searchTerm = preg_replace("/[\s,;]+/", '%', $_GET['query']);
            $query->Where(" concat(name,'|',ss_address_component.long_name,'|',ss_countries.country_name) like '%" . $searchTerm . "%'");
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

    public function actionCreate()
    {
        $data = json_decode(file_get_contents('php://input'));
        $model = new $this->modelClass();
        foreach ($data as $key => $value) {
            if ($model->hasAttribute($key)) $model->setAttribute($key, $value);
        }
        $transaction = Yii::$app->db->beginTransaction();
        if ($model->save()) {
            foreach ($data->addressComponent as $component) {
                if (!\app\models\AddressComponentTypes::find()->where(['name' => $component->type])->exists()) {
                    $type = new \app\models\AddressComponentTypes();
                    $type->name = $component->type;
                    $type->save();
                } else {
                    $type = \app\models\AddressComponentTypes::find()->where(['name' => $component->type])->one();
                }
                $addressComponent = new \app\models\AddressComponent();
                $addressComponent->long_name = $component->long_name;
                $addressComponent->short_name = $component->short_name;
                $addressComponent->type = $type->getPrimaryKey();
                $addressComponent->spots_id = $model->getPrimaryKey();
                if (!$addressComponent->save()) {
                    $transaction->rollBack();
                    return $addressComponent->getErrors();

                }
            }

            foreach ($data->photos as $photoUrl) {
                $photo = new \app\models\SpotPhoto();
                $photo->url = $photoUrl;
                $photo->spot_id = $model->getPrimaryKey();
                if (!$photo->save()) {
                    $transaction->rollBack();
                    return $photo->getErrors();

                }
            }
            $transaction->commit();
            return $model;
        } else {
            $transaction->rollBack();
            return $model->getErrors();

        }
    }

    public function actionGetByGoogleId()
    {
        $googleId = Yii::$app->getRequest()->getQueryParam('googleId');
        if ($googleId) {
            return Spot::find()
                ->where(['place_id' => $googleId])
                ->one();
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }

    public function actionGetByUserId()
    {
        $userId = Yii::$app->getRequest()->getQueryParam('userId');
        if ($userId) {
            $user = \app\models\User::findOne($userId);
            return $user->spots;
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }
}

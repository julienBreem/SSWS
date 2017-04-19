<?php
namespace app\controllers;

use app\models\Spot;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class TipsController extends Controller
{
    public $modelClass = 'app\models\Tip';

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

    public function actions()
    {
        $actions = parent::actions();
        return $actions;
    }

    public function actionGetByUserId()
    {
        $userId = Yii::$app->getRequest()->getQueryParam('userId');
        if($userId){
            return \app\models\Tip::find()
                ->where(['user_id' => $userId])
                ->all();
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }
    public function actionGetBySpotId()
    {
        $spotId = Yii::$app->getRequest()->getQueryParam('spotId');
        if($spotId){
            return \app\models\Tip::find()
                ->where(['spot_id' => $spotId])
                ->all();
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }
}

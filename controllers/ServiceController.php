<?php
namespace app\controllers;

use app\models\Identity;
use app\models\Spot;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class ServiceController extends Controller
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

        return $behaviors;
    }

    public function actions()
    {
        //$actions = parent::actions();
        //unset($actions['index']);
        //return $actions;
        return [];
    }

    public function actionTest()
    {
        return Yii::$app->user->getId();
    }

    public function actionSpot()
    {
        $spotId = Yii::$app->getRequest()->getQueryParam('spotId');
        if($spotId){
            $userId = Yii::$app->user->getId();
            $user = User::findOne($userId);
            $spot = Spot::findOne($spotId);

            if($spot->spotted){ // DEJA SPOTTED?
                $spot->unlink('spotters',$user);
                return false;
            } else { //SPOT
                $spot->link('spotters',$user);
                $spot->unlink('planners',$user,true);
            }
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }
    public function actionSpotlater()
    {
        $spotId = Yii::$app->getRequest()->getQueryParam('spotId');
        if($spotId){
            $userId = Yii::$app->user->getId();
            $user = User::findOne($userId);
            $spot = Spot::findOne($spotId);

            if($spot->planned){ // DEJA PLANNED?
                $spot->unlink('planners',$user);
                return false;
            } else { //SPOT
                $spot->link('planners',$user,true);
                return true;
            }
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }
}

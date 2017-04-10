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

    public function actionSpot()
    {
        $spotId = Yii::$app->getRequest()->getQueryParam('spotId');
        if($spotId){
            $userId = Yii::$app->user->getId();
            $user = User::findOne($userId);
            $spot = Spot::findOne($spotId);

            if($spot->spotted){ // DEJA SPOTTED?
                if(\Yii::$app
                    ->db
                    ->createCommand()
                    ->delete('ss_users_spots', ['spot_id' => $spotId, 'user_id' => $userId])
                    ->execute()){ // DELETE LE LIEN
                    return false;
                } else {
                    throw new \yii\web\HttpException(500, 'Deletion error');
                    //return $spot->getErrors();
                }
            } else { //SPOT
                if($spot->link('spotters',$user)){
                    \Yii::$app
                        ->db
                        ->createCommand()
                        ->delete('ss_users_spotlater', ['spot_id' => $spotId, 'user_id' => $userId])
                        ->execute();
                    return true;
                } else {
                    //throw new \yii\web\HttpException(500, 'Internal server error');
                    return $spot->getErrors();
                }
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
                if(\Yii::$app
                    ->db
                    ->createCommand()
                    ->delete('ss_users_spotlater', ['spot_id' => $spotId, 'user_id' => $userId])
                    ->execute()){ // DELETE LE LIEN
                    return false;
                } else {
                    throw new \yii\web\HttpException(500, 'Deletion error');
                    //return $spot->getErrors();
                }
            } else { //SPOT
                if($spot->link('planners',$user)){
                    return true;
                } else {
                    //throw new \yii\web\HttpException(500, 'Internal server error');
                    return $spot->getErrors();
                }
            }
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }
}

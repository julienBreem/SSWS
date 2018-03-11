<?php
namespace app\controllers;

use app\models\Category;
use app\models\Spot;
use app\models\User;
use app\models\UsersSpots;
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
        $spotId = Yii::$app->getRequest()->getBodyParam('spotId');
        if($spotId){
            $userId = Yii::$app->user->getId();
            $user = User::findOne($userId);
            $spot = Spot::findOne($spotId);

            if($spot->spotted){
                $spot->unlink('spotters',$user,true);
                return $spot;
            } else {
                $spot->link('spotters',$user);
                $spot->unlink('planners',$user,true);
            }
            $categories = Yii::$app->getRequest()->getBodyParam('categories');

            $usersSpots = UsersSpots::find()
                ->where([
                    'user_id' => $userId,
                    'spot_id' => $spotId
                ])
                ->one();
            foreach($categories as $categoryName){
                $category = Category::find()
                    ->where([
                        'category_name' => $categoryName
                    ])
                    ->one();
                $usersSpots->link('categories', $category);
            }

            return $usersSpots->spot;
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

            if($spot->planned){
                $spot->unlink('planners',$user,true);
            } else {
                $spot->link('planners',$user);
            }

            return $spot;
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }
}

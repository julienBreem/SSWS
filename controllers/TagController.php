<?php
namespace app\controllers;

use app\models\Spot;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class TagController extends Controller
{
    public $modelClass = 'app\models\Tag';

    public function behaviors()
    {

        $behaviors = parent::behaviors();
/*
        if($_SERVER['REQUEST_METHOD']!='OPTIONS'){
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
        return $actions;
    }
}

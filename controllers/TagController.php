<?php
namespace app\controllers;

use app\models\Tag;
use yii\data\ActiveDataProvider;

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
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => Tag::find(),
            'pagination' => [
                'defaultPageSize' => 0,
                'pageSizeLimit' => [0, 50],
            ],
        ]);
        return $activeData;
    }
}
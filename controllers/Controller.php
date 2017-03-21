<?php
namespace app\controllers;

use yii\rest\ActiveController;
use Yii;


class Controller extends ActiveController
{
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'],
            'view' => ['GET', 'HEAD', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'update' => ['PUT', 'PATCH', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
        ];
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        return $behaviors;
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            $this->actionOptions();
        }

        return true;
    }

    public function actionOptions ()
    {
        if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            Yii::$app->getResponse()->setStatusCode(405);
        }
        Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST GET PUT DELETE');
        Yii::$app->end();
    }
}
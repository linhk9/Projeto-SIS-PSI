<?php

namespace backend\modules\api\controllers;

use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class FavoritoController extends ActiveController
{
    public $modelClass = 'common\models\Favoritos';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'authf']
        ];
        return $behaviors;
    }
    //Header: Authorization 'Basic'.base64($username.':'.$password);

    public function authf($username, $password)
    {
        $user = \common\models\User::findByUsername($username);
        if ($user && $user->validatePassword($password))
        {
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('Falha na autenticação'); //403
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['update'], $actions['index'], $actions['view']);

        return $actions;
    }

    public function actionFavoritosuserdata($id_userdata)
    {
        $model = new $this->modelClass;

        return $model::find()->where(['id_userdata' => $id_userdata])->all();
    }
}

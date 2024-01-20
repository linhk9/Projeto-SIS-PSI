<?php

namespace backend\modules\api\controllers;

use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class FaturaController extends ActiveController
{
    public $modelClass = 'common\models\Faturas';
    public $modelClassLinhas = 'common\models\FaturaLinhas';

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

        unset($actions['delete'], $actions['create'], $actions['update'], $actions['index'], $actions['view']);

        return $actions;
    }

    public function actionFaturauserdata($id_userdata)
    {
        $model = new $this->modelClass;
        $modelFaturaLinhas = new $this->modelClassLinhas;

        $faturas = $model->find()->where(['id_userdata' => $id_userdata])->all();
        if (!$faturas) {
            throw new \yii\web\NotFoundHttpException('Faturas não foram encontradas');
        }

        $faturasData = [];
        foreach ($faturas as $fatura) {
            $faturaLinhas = $modelFaturaLinhas->find()->where(['id_fatura' => $fatura->id])->all();
            if (!$faturaLinhas) {
                throw new \yii\web\NotFoundHttpException('Fatura Linhas não foram encontradas');
            }

            $faturasData[] = [
                'id' => $fatura->id,
                'id_userdata' => $fatura->id_userdata,
                'data' => $fatura->data,
                'faturaLinhas' => $faturaLinhas
            ];
        }

        return $faturasData;
    }
}
<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class FaturaController extends ActiveController
{
    public $modelClass = 'common\models\Faturas';
    public $modelClassLinhas = 'common\models\FaturaLinhas';

    public function actionFaturauserdata($id_userdata)
    {
        $model = new $this->modelClass;
        $modelFaturaLinhas = new $this->modelClassLinhas;

        $fatura = $model->find()->where(['id_userdata' => $id_userdata])->one();
        if (!$fatura) {
            throw new \yii\web\NotFoundHttpException('Fatura não foi encontrada');
        }

        $faturaLinhas = $modelFaturaLinhas->find()->where(['id_fatura' => $fatura->id])->all();
        if (!$faturaLinhas) {
            throw new \yii\web\NotFoundHttpException('Fatura Linhas não foi encontrada');
        }

        return [
            'id' => $fatura->id,
            'id_userdata' => $fatura->id_userdata,
            'data' => $fatura->data,
            'faturaLinhas' => $faturaLinhas
        ];
    }
}
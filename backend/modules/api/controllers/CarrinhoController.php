<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class CarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\Carrinho';
    public $modelClassLinhas = 'common\models\CarrinhoLinhas';

    public function actionCarrinhouserdata($id_userdata)
    {
        $model = new $this->modelClass;
        $modelCarrinhoLinhas = new $this->modelClassLinhas;

        $carrinho = $model->find()->where(['id_userdata' => $id_userdata])->one();
        if (!$carrinho) {
            throw new \yii\web\NotFoundHttpException('Carrinho não foi encontrado');
        }

        $carrinhoLinhas = $modelCarrinhoLinhas->find()->where(['id_carrinho' => $carrinho->id])->all();
        if (!$carrinhoLinhas) {
            throw new \yii\web\NotFoundHttpException('Carrinho Linhas não foi encontrado');
        }

        return [
            'id' => $carrinho->id,
            'id_userdata' => $carrinho->id_userdata,
            'data' => $carrinho->data,
            'carrinhoLinhas' => $carrinhoLinhas
        ];
    }
}
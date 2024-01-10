<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class CarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\Carrinho';
    public $modelClassCarrinhoLinhas = 'common\models\CarrinhoLinhas';

    public function actionCarrinhouserdata($id_userdata)
    {
        $model = new $this->modelClass;
        $modelCarrinhoLinhas = new $this->modelClassCarrinhoLinhas;

        $carrinho = $model->find()->where(['id_userdata' => $id_userdata])->one();
        $carrinhoLinhas = $modelCarrinhoLinhas->find()->where(['id_carrinho' => $carrinho->id])->all();

        return [
            'id' => $carrinho -> id,
            'id_userdata' => $carrinho -> id_userdata,
            'data' => $carrinho -> data,
            'carrinhoLinhas' => $carrinhoLinhas
        ];
    }
}
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
    public $modelClassFaturas = 'common\models\Faturas';
    public $modelClassFaturaLinhas = 'common\models\FaturaLinhas';

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

    public function actionDeletelinha($id)
    {
        $model = $this->modelClass;
        $modelLinhas = $this->modelClassLinhas;

        $carrinhoLinha = $modelLinhas::findOne($id);
        if ($carrinhoLinha === null) {
            throw new \yii\web\BadRequestHttpException('Esta linha não existe');
        }

        $carrinhoId = $carrinhoLinha->id_carrinho;
        $carrinhoLinha->delete();

        $itemsRestantes= $modelLinhas::find()->where(['id_carrinho' => $carrinhoId])->one();
        if ($itemsRestantes === null) {
            $carrinho = $model::findOne($carrinhoId);
            if ($carrinho !== null) {
                $carrinho->delete();
            }
        }

        return [
            'menssage' => 'Produto eliminado com sucesso'
        ];

    }

    public function actionCheckout($id_userdata)
    {
        $model = $this->modelClass;
        $modelFatura = $this->modelClassFaturas;
        $modelFaturaLinhas = $this->modelClassFaturaLinhas;

        $fatura = new $modelFatura();
        $fatura->id_userdata = $id_userdata;
        $fatura->data = date('Y-m-d');
        $fatura->save();

        $carrinho = $model::find()->where(['id_userdata' => $id_userdata])->one();
        if ($carrinho !== null) {
            $carrinhoLinhas = $carrinho->getCarrinhoLinhas()->all();
            foreach ($carrinhoLinhas as $linha) {
                $produto = $linha->produto;
                if ($linha->quantidade > $produto->stock) {
                    throw new \yii\web\BadRequestHttpException('Não existe stock suficiente de ' . $produto->nome);
                }
                $produto->stock -= $linha->quantidade;
                $produto->save();

                $faturaLinha = new $modelFaturaLinhas();
                $faturaLinha->id_fatura = $fatura->id;
                $faturaLinha->id_produto = $linha->id_produto;
                $faturaLinha->quantidade = $linha->quantidade;
                $faturaLinha->preco = $linha->preco;
                $faturaLinha->save();
                $linha->delete();
            }
            $carrinho->delete();
        }

        return [
            'menssage' => 'Checkout feito com sucesso'
        ];
    }



}
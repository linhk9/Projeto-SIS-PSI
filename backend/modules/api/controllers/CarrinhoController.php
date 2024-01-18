<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\filters\auth\HttpBasicAuth;
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

    public function actionAtualizarquantidade($id_linha)
    {
        $model = $this->modelClassLinhas;

        $carrinhoLinha = $model::findOne($id_linha);
        if ($carrinhoLinha === null) {
            throw new \yii\web\BadRequestHttpException('Esta linha não existe');
        }

        $carrinhoLinha->quantidade = Yii::$app->request->post('quantidade');
        $carrinhoLinha->save();

        return [
            'message' => 'Quantidade atualizada com sucesso'
        ];
    }

    public function actionDeletelinha($id_linha)
    {
        $model = $this->modelClass;
        $modelLinhas = $this->modelClassLinhas;

        $carrinhoLinha = $modelLinhas::findOne($id_linha);
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
            'message' => 'Produto eliminado com sucesso'
        ];
    }

    public function actionAdicionarlinha($id_userdata, $id_produto)
    {
        $model = $this->modelClass;
        $modelLinhas = $this->modelClassLinhas;

        $carrinho = $model::find()->where(['id_userdata' => $id_userdata])->one();
        if ($carrinho === null) {
            $carrinho = new $model();
            $carrinho->id_userdata = $id_userdata;
            $carrinho->data = date('Y-m-d');
            $carrinho->save();
        }

        $carrinhoLinha = $modelLinhas::find()->where(['id_carrinho' => $carrinho->id, 'id_produto' => $id_produto])->one();
        if ($carrinhoLinha === null) {
            $carrinhoLinha = new $modelLinhas();
            $carrinhoLinha->id_carrinho = $carrinho->id;
            $carrinhoLinha->id_produto = $id_produto;
            $carrinhoLinha->quantidade = 1;
            $carrinhoLinha->preco = $carrinhoLinha->produto->preco;
        } else {
            $carrinhoLinha->quantidade += 1;
        }
        $carrinhoLinha->save();

        return [
            'message' => 'Produto adicionado ao carrinho com sucesso'
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
            'message' => 'Checkout feito com sucesso'
        ];
    }
}
<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class ProdutoController extends ActiveController
{
    public $modelClass = 'common\models\Produtos';

    public function actionComnomecategoria()
    {
        $produtos = $this->modelClass::find()->all();

        $produtosComCategoria = [];
        foreach ($produtos as $produto) {
            $produtosComCategoria[] = [
                'id' => $produto->id,
                'id_categoria' => $produto->id_categoria,
                'categoria' => $produto->categoria->nome,
                'nome' => $produto->nome,
                'descricao' => $produto->descricao,
                'preco' => $produto->preco,
                'stock' => $produto->stock,
                'imagem' => $produto->imagem,
                'marca' => $produto->marca,
                'tamanho' => $produto->tamanho,
                'cores' => $produto->cores,
            ];
        }

        return $produtosComCategoria;
    }

}

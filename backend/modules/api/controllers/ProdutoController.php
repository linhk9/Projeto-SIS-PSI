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
    public $modelClassPromocao = 'common\models\Promocoes';

    public function actionComnomecategoria()
    {
        $model = $this->modelClass;
        $modelPromocao = $this->modelClassPromocao;

        $produtos = $model::find()->all();

        $produtosComCategoria = [];
        foreach ($produtos as $produto) {
            $promocao = $modelPromocao::findOne(['id_produto' => $produto->id]);
            if ($promocao) {
                $precoPromocao = $produto->preco - ($produto->preco * $promocao->desconto / 100);

                $produtosComCategoria[] = [
                    'id' => $produto->id,
                    'id_categoria' => $produto->id_categoria,
                    'categoria' => $produto->categoria->nome,
                    'nome' => $produto->nome,
                    'descricao' => $produto->descricao,
                    'preco' => $precoPromocao,
                    'preco_antigo' => $produto->preco,
                    'stock' => $produto->stock,
                    'imagem' => $produto->imagem,
                    'marca' => $produto->marca,
                    'tamanho' => $produto->tamanho,
                    'cores' => $produto->cores,
                ];
            } else {
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
        }

        return $produtosComCategoria;
    }

}

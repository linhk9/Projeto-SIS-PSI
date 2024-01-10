<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "carrinho_linhas".
 *
 * @property int $id
 * @property int|null $id_carrinho
 * @property int|null $id_produto
 * @property int|null $quantidade
 * @property float|null $preco
 *
 * @property Carrinho $carrinho
 * @property Produtos $produto
 */
class CarrinhoLinhas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrinho_linhas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_carrinho', 'id_produto', 'quantidade'], 'integer'],
            [['preco'], 'number'],
            [['id_carrinho'], 'exist', 'skipOnError' => true, 'targetClass' => Carrinho::class, 'targetAttribute' => ['id_carrinho' => 'id']],
            [['id_produto'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::class, 'targetAttribute' => ['id_produto' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_carrinho' => 'Id Carrinho',
            'id_produto' => 'Id Produto',
            'quantidade' => 'Quantidade',
            'preco' => 'Preco',
        ];
    }

    /**
     * Gets query for [[Carrinho]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrinho()
    {
        return $this->hasOne(Carrinho::class, ['id' => 'id_carrinho']);
    }

    /**
     * Gets query for [[Produto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduto()
    {
        return $this->hasOne(Produtos::class, ['id' => 'id_produto']);
    }
}

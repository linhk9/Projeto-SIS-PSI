<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fatura_linhas".
 *
 * @property int $id
 * @property int|null $id_fatura
 * @property int|null $id_produto
 * @property int|null $quantidade
 * @property float|null $preco
 *
 * @property Faturas $fatura
 * @property Produtos $produto
 */
class FaturaLinhas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fatura_linhas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_fatura', 'id_produto', 'quantidade'], 'integer'],
            [['preco'], 'number'],
            [['id_fatura'], 'exist', 'skipOnError' => true, 'targetClass' => Faturas::class, 'targetAttribute' => ['id_fatura' => 'id']],
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
            'id_fatura' => 'Id Fatura',
            'id_produto' => 'Id Produto',
            'quantidade' => 'Quantidade',
            'preco' => 'Preco',
        ];
    }

    /**
     * Gets query for [[Fatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFatura()
    {
        return $this->hasOne(Faturas::class, ['id' => 'id_fatura']);
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

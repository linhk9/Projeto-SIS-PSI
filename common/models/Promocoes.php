<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "promocoes".
 *
 * @property int $id
 * @property int|null $id_produto
 * @property int|null $desconto
 * @property string|null $data_inicio
 * @property string|null $data_termino
 *
 * @property Produtos $produto
 */
class Promocoes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promocoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_produto', 'desconto'], 'integer'],
            [['data_inicio', 'data_termino'], 'safe'],
            [['id_produto'], 'unique'],
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
            'id_produto' => 'Produto',
            'desconto' => 'Desconto',
            'data_inicio' => 'Data Inicio',
            'data_termino' => 'Data Termino',
        ];
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

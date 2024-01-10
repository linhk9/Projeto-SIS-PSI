<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "carrinho".
 *
 * @property int $id
 * @property int $id_userdata
 * @property string|null $data
 *
 * @property CarrinhoLinhas[] $carrinhoLinhas
 * @property Userdata $userdata
 */
class Carrinho extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrinho';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_userdata'], 'required'],
            [['id_userdata'], 'integer'],
            [['id_userdata'], 'unique'],
            [['data'], 'safe'],
            [['id_userdata'], 'exist', 'skipOnError' => true, 'targetClass' => Userdata::class, 'targetAttribute' => ['id_userdata' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_userdata' => 'Id Userdata',
            'data' => 'Data',
        ];
    }

    /**
     * Gets query for [[CarrinhoLinhas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrinhoLinhas()
    {
        return $this->hasMany(CarrinhoLinhas::class, ['id_carrinho' => 'id']);
    }

    /**
     * Gets query for [[Userdata]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserdata()
    {
        return $this->hasOne(Userdata::class, ['id' => 'id_userdata']);
    }
}

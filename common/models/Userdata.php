<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "userdata".
 *
 * @property int $id
 * @property int|null $id_user
 * @property string|null $primeiroNome
 * @property string|null $ultimoNome
 * @property int|null $telemovel
 * @property string|null $morada
 *
 * @property User $user
 */
class Userdata extends \yii\db\ActiveRecord
{
    public $role;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userdata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['role', 'string'],
            [['id_user', 'telemovel'], 'integer'],
            [['primeiroNome', 'ultimoNome'], 'string', 'max' => 45],
            [['morada'], 'string', 'max' => 255],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'User ID',
            'primeiroNome' => 'Primeiro Nome',
            'ultimoNome' => 'Ultimo Nome',
            'telemovel' => 'Telemovel',
            'morada' => 'Morada',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }
}

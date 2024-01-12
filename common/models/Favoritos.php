<?php

namespace common\models;

use app\mosquitto\phpMQTT;
use Yii;

/**
 * This is the model class for table "favoritos".
 *
 * @property int $id
 * @property int|null $id_userdata
 * @property int|null $id_produto
 *
 * @property Produtos $produto
 * @property User $userdata
 */
class Favoritos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favoritos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_userdata', 'id_produto'], 'integer'],
            [['id_produto'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::class, 'targetAttribute' => ['id_produto' => 'id']],
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
            'id_produto' => 'Id Produto',
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

    /**
     * Gets query for [[Userdata]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserdata()
    {
        return $this->hasOne(Userdata::class, ['id' => 'id_userdata']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $myObj=new \stdClass();
        $myObj->mensagem='ALERTA: Novo produto adicionado aos favoritos';
        $myJSON = json_encode($myObj);

        $this->FazPublishNoMosquitto("favoritos",$myObj);

    }

    public function FazPublishNoMosquitto($canal,$msg)
    {
        $server = "127.0.0.1";
        $port = 1883;
        $username = "";
        $password = "";
        $client_id = "phpMQTT-publisher";
        $mqtt = new phpMQTT($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        } else {
            file_put_contents("debug.output", "Time out!");
        }
    }
}

<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class FavoritoController extends ActiveController
{
    public $modelClass = 'common\models\Favoritos';

    public function actionFavoritosuserdata($id_userdata)
    {
        $model = new $this->modelClass;

        return $model::find()->where(['id_userdata' => $id_userdata])->all();

    }

    public function actionFavoritosadd($id_userdata, $id_produto)
    {
        $model = new $this->modelClass;

        $model->id_userdata = $id_userdata;
        $model->id_produto = $id_produto;

        $model->save();

        return $model;
    }

    public function actionFavoritodelete($id_userdata, $id_produto)
    {
        $model = new $this->modelClass;

        $model = $model::find()->where(['id_userdata' => $id_userdata, 'id_produto' => $id_produto])->one();

        $model->delete();

        return $model;
    }

    public function actionFavoritosupdate($id_userdata, $id_produto, $id_new_produto)
    {
        $model = new $this->modelClass;

        $model = $model::find()->where(['id_userdata' => $id_userdata, 'id_produto' => $id_produto])->one();

        $model->id_produto = $id_new_produto;

        $model->save();

        return ['mensagem' => 'Produto atualizado com sucesso!'];
    }
}

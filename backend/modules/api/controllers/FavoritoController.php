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
}

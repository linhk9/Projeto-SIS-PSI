<?php

namespace backend\modules\api\components;

use Yii;
use yii\filters\auth\AuthMethod;

class CustomAuth extends AuthMethod
{
    public function authenticate($user, $request, $response)
    {
        $authToken = $request->getQueryString();
        $token = explode('=', $authToken)[1];
        $user = \common\models\User::findIdentityByAccessToken($token);

        if ($user === null) {
            throw new \yii\web\ForbiddenHttpException('Falha na autenticação'); //403
        }

        Yii::$app->params['id'] = $user->id;
        return $user;
    }
}
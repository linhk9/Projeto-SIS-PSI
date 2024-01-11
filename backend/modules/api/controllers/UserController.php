<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public $modelClassUserdata = 'common\models\Userdata';


//    public function behaviors()
//    {
//        Yii::$app->params['id'] = 0;
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => CustomAuth::className(),
//        ];
//        return $behaviors;
//    }

//    public function checkAccess($action, $model = null, $params = [])
//    {
//        if(Yii::$app->params['id'] == 1)
//        {
//            if($action==="delete")
//            {
//                throw new \yii\web\ForbiddenHttpException('Proibido');
//            }
//        }
//    }

    public function actionPerfil($id)
    {
        $model = $this->modelClass;
        $user = $model::findOne($id);

        $modelUserdata = $this->modelClassUserdata;
        $userdata = $modelUserdata::findOne(['id_user' => $user->id]);

        return [
            'id' => $userdata->id,
            'username' => $user->username,
            'email' => $user->email,
            'status' => $user->status,

            'primeiroNome' => $userdata->primeiroNome,
            'ultimoNome' => $userdata->ultimoNome,
            'telemovel' => $userdata->telemovel,
            'morada' => $userdata->morada,
        ];
    }

    public function actionLogin()
    {
        $model = $this->modelClass;

        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        if (!isset($username, $password)) {
            throw new \yii\web\BadRequestHttpException('Parâmetros inválidos');
        }

        $user = $model::findByUsername($username);
        if (!$user || !$user->validatePassword($password)) {
            throw new \yii\web\UnauthorizedHttpException('Parâmetros inválidas');
        }

        return $user;
    }

    public function actionRegisto()
    {
        $model = $this->modelClass;
        $modelUserdata = $this->modelClassUserdata;

        $user = new $model();
        $userData = new $modelUserdata();

        $username = \Yii::$app->request->post('username');
        $email = \Yii::$app->request->post('email');
        $password = \Yii::$app->request->post('password');
        $primeiroNome = \Yii::$app->request->post('primeiroNome');
        $ultimoNome = \Yii::$app->request->post('ultimoNome');
        $telemovel = \Yii::$app->request->post('telemovel');
        $morada = \Yii::$app->request->post('morada');


        if (!isset($email, $primeiroNome, $ultimoNome, $telemovel, $morada)) {
            throw new \yii\web\BadRequestHttpException('Parâmetros inválidos');
        }

        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = 10;

        if (!$user->save()) {
            throw new \yii\web\ServerErrorHttpException('Erro ao criar o utilizador.');
        }

        $userData->id_user = $user->id;
        $userData->primeiroNome = $primeiroNome;
        $userData->ultimoNome = $ultimoNome;
        $userData->telemovel = $telemovel;
        $userData->morada = $morada;

        if (!$userData->save()) {
            throw new \yii\web\ServerErrorHttpException('Erro ao criar os dados do utilizador.');
        }

        return [
            'mensagem' => 'Utilizador criado com sucesso'
        ];
    }

    public function actionAtualizarperfil($id)
    {
        $model = $this->modelClass;
        $modelUserdata = $this->modelClassUserdata;

        $email = Yii::$app->request->post('email');
        $primeiroNome = Yii::$app->request->post('primeiroNome');
        $ultimoNome = Yii::$app->request->post('ultimoNome');
        $telemovel = Yii::$app->request->post('telemovel');
        $morada = Yii::$app->request->post('morada');

        if (!isset($email, $primeiroNome, $ultimoNome, $telemovel, $morada)) {
            throw new \yii\web\BadRequestHttpException('Parâmetros inválidos');
        }

        $user = $model::findOne($id);
        if (!$user) {
            throw new \yii\web\NotFoundHttpException("Utilizador não encontrado.");
        }

        $user->email = $email;
        if (!$user->save()) {
            throw new \yii\web\ServerErrorHttpException('Erro ao atualizar o utilizador.');
        }

        $userdata = $modelUserdata::findOne(['id_user' => $user->id]);
        if (!$userdata) {
            throw new \yii\web\NotFoundHttpException("Dados do utilizador não encontrados.");
        }

        $userdata->primeiroNome = $primeiroNome;
        $userdata->ultimoNome = $ultimoNome;
        $userdata->telemovel = $telemovel;
        $userdata->morada = $morada;

        if (!$userdata->save()) {
            throw new \yii\web\ServerErrorHttpException('Erro ao atualizar os dados do utilizador.');
        }

        return [
            'mensagem' => 'Perfil atualizado com sucesso'
        ];
    }
}

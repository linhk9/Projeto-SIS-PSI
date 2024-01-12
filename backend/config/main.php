<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers'=>[
                'application/json'=>'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // TODO: Bloquear actions que não são necessárias

                // TODO: Editar Perfil

                // TODO: Calcular Promoção nos Produtos (ação comnomecategoria)
                //  (enviar preço antigo caso exista promoção)

                [
                    'class' => 'yii\rest\UrlRule','controller' => 'api/user',
                    'extraPatterns' => [
                        'GET {id}/userdata' => 'perfil',

                        'POST login' => 'login',
                        'POST registo' => 'registo',

                        'PUT {id}/atualizar' => 'atualizarperfil',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule','controller' => 'api/favorito',
                    'extraPatterns' =>[
                        'GET userdata/{id_userdata}' => 'favoritosuserdata',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{id_userdata}' => '<id_userdata:\\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule','controller' => 'api/produto',
                    'extraPatterns' =>[
                        'GET comnomecategoria' => 'comnomecategoria',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule','controller' => 'api/carrinho',
                    'extraPatterns' =>[
                        'GET userdata/{id_userdata}' => 'carrinhouserdata',
                        'DELETE linha/{id}' => 'deletelinha',
                        'DELETE checkout/{id_userdata}' => 'checkout',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{id_userdata}' => '<id_userdata:\\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule','controller' => 'api/fatura',
                    'extraPatterns' =>[
                        'GET userdata/{id_userdata}' => 'faturauserdata',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{id_userdata}' => '<id_userdata:\\d+>',
                    ],
                ],
            ],
        ],

    ],
    'params' => $params,
];

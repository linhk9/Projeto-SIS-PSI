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
                [
                    'class' => 'yii\rest\UrlRule','controller' => 'api/user',
                    'extraPatterns' => [
                        'GET {id}/userdata' => 'perfil',
                        'PUT {id}/atualizar' => 'atualizarperfil',

                        'POST login' => 'login',
                        'POST registo' => 'registo',
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
                        'POST checkout/{id_userdata}' => 'checkout',
                        'POST linha/{id_userdata}/{id_produto}' => 'adicionarlinha',
                        'DELETE linha/{id_linha}' => 'deletelinha',
                        'PUT linha/{id_linha}/qtd' => 'atualizarquantidade',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{id_linha}' => '<id_linha:\\d+>',
                        '{id_produto}' => '<id_produto:\\d+>',
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

<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/manage',
        ],
        'user' => [
            'loginUrl' => ['/login'],
            'identityCookie' => [
                'name'     => '_backendIdentity',
                'path'     => '/manage',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            'name' => 'BACKENDSESSID',
            'cookieParams' => [
                'httpOnly' => true,
                'path'     => '/manage',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'default/error',
        ],
        // запрещаем bootstrap на корню, у нас свой (из темы coreui)
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null, 'css' => []
                ],
                'yii\bootstrap\BootstrapThemeAsset' => [
                    'sourcePath' => null, 'css' => []
                ],
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@backend/views/user',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'default/index',
                '<action:login>' => '/user/security/login',
                '<action:logout>' => '/user/security/logout',
                '<action:users>' => '/user/admin/index',
                '<controller:users>/<action:create>' => '/user/admin/create',
                '<controller:users>/<action:update>' => '/user/admin/update',
                '<controller:users>/<action:delete>' => '/user/admin/delete',
                '<controller:users>/<action:update-profile>' => '/user/admin/update-profile',
                '<controller:users>/<action:assignments>' => '/user/admin/assignments',
                '<action:profile>' => 'user/settings/<action>',
                '<action:account>' => 'user/settings/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
            ],
        ],
    ],
    'params' => $params,
];

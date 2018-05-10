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
         'admin' => [
              'basePath' => '@app/modules/admin',
            'class' => 'backend\modules\admin\admin',
        ],
         'gii' => [
            'class' => 'yii\gii\Module',
        ],
    ],
      'homeUrl' => '/webpanel',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
               'baseUrl' => '/webpanel',
        ],
         'user' => [
            'identityClass' => 'common\models\User', // your admin model
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => 'admin', 'httpOnly' => true],
                'returnUrl' => array('/admin/site/index'),
            'loginUrl' => array('/admin/site/login'),
        ],
//        'user' => [
//            'identityClass' => 'common\models\User',
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
//        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        
    ],
    'params' => $params,
];

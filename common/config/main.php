<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'myclass'=>['class'=>'common\components\Myclass'],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
       'modules' => [
     'gridview' => ['class' => 'kartik\grid\Module']
],
];

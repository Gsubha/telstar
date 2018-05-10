<?php

namespace frontend\modules\site;

use Yii;
use yii\base\Module;

/**
 * user module definition class
 */
class user extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\site\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::$app->session['module_name'] = "Module";
        parent::init();

        // custom initialization code goes here
          $this->layout = "@app/modules/site/views/layouts/main";

        //$homeurl = Yii::$app->getHomeUrl() . 't1/site/index/';
        //Yii::$app->setHomeUrl($homeurl);        
        
        parent::init(); 
           
       //$session = Yii::$app->session;
  
        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
//            // 'loginUrl' => ['yonetim/default/login'],
            'identityCookie' => ['name' => 'site', 'httpOnly' => true],
//            //  'idParam' => 'editor_id', //this is important !
            'returnUrl' => array('site/site/index'),
            'loginUrl' => array('site/site/login'),
        ]);

    }
}

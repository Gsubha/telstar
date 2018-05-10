<?php

namespace backend\modules\admin;


use Yii;
use yii\base\Module;
use yii\base\Theme;
/**
 * admin module definition class
 */
class admin extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::$app->session['module_name'] = "Module";
        parent::init();

        // custom initialization code goes here
        
         $this->layout = "@app/modules/admin/views/layouts/main";

        $homeurl = Yii::$app->getHomeUrl() . '/admin/site/index';
        Yii::$app->setHomeUrl($homeurl);        
        
        parent::init(); 
           
       //$session = Yii::$app->session;
  
//        Yii::$app->set('user', [
//            'class' => 'yii\web\User',
//            'identityClass' => 'common\models\DlAdmin',
//            'enableAutoLogin' => true,
//            // 'loginUrl' => ['yonetim/default/login'],
//            'identityCookie' => ['name' => 'admin', 'httpOnly' => true],
//            //  'idParam' => 'editor_id', //this is important !
//            'returnUrl' => array('admin/default/index'),
//            'loginUrl' => array('admin/site/login'),
//        ]);

        Yii::$app->view->theme = new Theme([
            'pathMap' => ['@app/views' => '@app/themes/admin/views'],
            'baseUrl' => '@web/themes/admin',
        ]);
    }
}

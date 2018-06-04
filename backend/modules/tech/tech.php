<?php

namespace backend\modules\tech;


use Yii;
use yii\base\Module;
use yii\base\Theme;
/**
 * admin module definition class
 */
class tech extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\tech\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        
        
         if (isset(Yii::$app->session['module_name'])) {
             
            if (Yii::$app->session['module_name'] != 'tech') {
                Yii::$app->user->logout();
                Yii::$app->session['module_name'] = "tech";
            }
        } else {
            Yii::$app->session['module_name'] = "tech";
        }

           $this->layout = "@app/modules/tech/views/layouts/main";
                   
           $homeurl = Yii::$app->getHomeUrl() . '/tech/site/index';
        Yii::$app->setHomeUrl($homeurl);

        parent::init();
           
       //$session = Yii::$app->session;
  
        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\Tech',
            'enableAutoLogin' => true,
            // 'loginUrl' => ['yonetim/default/login'],
            'identityCookie' => ['name' => 'tech', 'httpOnly' => true],
            //  'idParam' => 'editor_id', //this is important !
            'returnUrl' => array('tech/site/index'),
            'loginUrl' => array('tech/site/login'),
        ]);

        Yii::$app->view->theme = new Theme([
            'pathMap' => ['@app/views' => '@app/themes/tech/views'],
            'baseUrl' => '@web/themes/tech',
        ]);
    }
}

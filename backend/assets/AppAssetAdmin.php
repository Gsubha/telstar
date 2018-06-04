<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAssetAdmin extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/admin';
    public $css = [
        'css/blue.css',
        'css/bootstrap.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css',
        'css/AdminLTE.min.css',
        'css/_all-skins.min.css',
        'css/morris.css',
        'css/jquery-jvectormap-1.2.2.css',
        'css/datepicker3.css',
        'css/daterangepicker.css',   
//        'css/bootstrap-colorpicker.min.css',
//        'css/bootstrap-timepicker.min.css',
        'css/bootstrap3-wysihtml5.min.css',
          'css/custom.css',
    ];
     public $js = [
     
//         'js/jquery-3.1.1.min.js',
//         'js/jquery-1.12.4.min.js',
        
         'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js',
         
        'js/bootstrap.min.js',
         
        'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',        
         
        'js/morris.min.js',
         
        'js/jquery.sparkline.min.js',
         
        'js/jquery-jvectormap-1.2.2.min.js',
         
        'js/jquery-jvectormap-world-mill-en.js',
         
        'js/jquery.knob.js',
         
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js',
         
        'js/daterangepicker.js',     
         
//        'js/datepicker/bootstrap-datepicker.js',     
         
        'js/bootstrap3-wysihtml5.all.min.js', 
         
        'js/jquery.slimscroll.min.js',  
         
        'js/fastclick.js',    
         
        'js/adminlte.min.js',
         
        'js/pages/dashboard.js',
         
        'js/demo.js',
//          'plugins/jQuery/jquery-3.1.1.min.js',
//            'plugins/select2/select2.full.min.js',
//            'plugins/input-mask/jquery.inputmask.js',
//            'plugins/input-mask/jquery.inputmask.date.extensions.js',
//            'plugins/input-mask/jquery.inputmask.extensions.js',
//            'plugins/daterangepicker/daterangepicker.js',
            'plugins/datepicker/bootstrap-datepicker.js',
//            'plugins/colorpicker/bootstrap-colorpicker.min.js',
//         'plugins/timepicker/bootstrap-timepicker.min.js',
//         'plugins/iCheck/icheck.min.js',
//         'plugins/fastclick/fastclick.js',
        
    ];
      public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
   
}

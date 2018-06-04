<?php

use common\models\User;
use yii\widgets\Menu;

$user=User::find()->where(['id'=>Yii::$app->user->id])->one();
?>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar customsidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="/backend/web/themes/tech/img/avatar.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p>Welcome  <?php echo $user->firstname.' '. $user->lastname ?></p>
            <p>   Tech ID: <?php echo $user->techid ?></p>
         </p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
        <?php
        echo Menu::widget([
            'options' => ['class' => 'sidebar-menu'],
            'encodeLabels' => false,
            'activateParents' => true,
            'activateItems' => true,
            'items' => [
                    ['label' => '<i class="fa fa-dashboard"></i> <span>Dashboard</span>', 'url' => ['site/index']],
//                    ['label' => '<i class="fa fa-users"></i> <span>Tech Management</span>', 'url' => ['tech/index'] ],
                  ['label' => '<i class="fa fa-fw fa-cloud-upload"></i> <span>Billing Details</span>', 'url' => ['billing/index'] ],
//                    'url' => ['#'],
//                    'options' => ['class' => 'treeview'],
//                    'items' => [
//                            ['label' => '<i class="fa fa-circle-o"></i>Types', 'url' => ['user-types/index']],
//                            ['label' => '<i class="fa fa-circle-o"></i>Users', 'url' => ['users/index']],
//                    ]],
                       
                    
            ],
            'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
        ]);
        ?>
    </section>
    <!-- /.sidebar -->
  </aside>
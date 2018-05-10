<?php

use yii\widgets\Menu;
?>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="/backend/web/themes/admin/img/avatar5.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Admin</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <!--<form action="#" method="get" class="sidebar-form">-->
<!--        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>-->
      <!--</form>-->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
     <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php
        echo Menu::widget([
            'options' => ['class' => 'sidebar-menu'],
            'encodeLabels' => false,
            'activateParents' => true,
            'activateItems' => true,
            'items' => [
                    ['label' => '<i class="fa fa-dashboard"></i> <span>Dashboard</span>', 'url' => ['site/index']],
                    ['label' => '<i class="fa fa-users"></i> <span>Tech Management</span>', 'url' => ['tech/index'] ],
                  ['label' => '<i class="fa fa-fw fa-cloud-upload"></i> <span>Billing and Import</span>', 'url' => ['billing/index'] ],
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
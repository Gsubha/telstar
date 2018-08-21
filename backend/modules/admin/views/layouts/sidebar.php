<?php

use yii\widgets\Menu;

?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar customsidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/backend/web/themes/admin/img/avatar.png" class="img-circle" alt="User Image">
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
                ['label' => '<i class="fa fa-fw fa-cloud-upload"></i> <span>Tech Codes & Import</span>', 'url' => ['billing/index']],
                ['label' => '<i class="fa fa-users"></i> <span>Tech Management</span>', 'url' => ['tech/index']],
                ['label' => '<i class="fa fa-envelope"></i> <span>Portal Management</span>', 'url' => ['portal/index']],
                ['label' => '<i class="fa fa-user-plus"></i> <span>Vendor Management</span>', 'url' => ['vendor/index']],
                ['label' => '<i class="fa fa-map-signs"></i> <span>Location Management</span>', 'url' => ['location/index']],
                ['label' => '<i class="fa fa-bar-chart"></i> <span>Reports</span><i class="fa pull-right fa-angle-left"></i>',
                    'url' => ['#'],                    
                    'options' => ['class' => 'treeview'],                   
                    'items' => [
                        ['label' => '<i class="fa fa-angle-double-right"></i> <span>Tech Overview</span>', 'url' => ['billing/tech-overview']],   
                        ['label' => '<i class="fa fa-angle-double-right"></i> <span>Tech Deduction Report</span>', 'url' => ['reports/techdeductions']],  
                    ]
                ],
                ['label' => '<i class="fa fa-upload"></i> <span>Upload Management</span>', 'url' => ['import-files/index']],
               // ['label' => '<i class="fa fa-users"></i> <span>CSR Management</span>', 'url' => ['tech/sadmin']],
               // ['label' => '<i class="fa fa-align-justify"></i> <span>My Works</span>', 'url' => ['tech/myworks']],



//                    'url' => ['#'],
//                    'options' => ['class' => 'treeview'],
//                    'items' => [
//                            ['label' => '<i class="fa fa-circle-o"></i>Types', 'url' => ['user-types/index']],
//                            ['label' => '<i class="fa fa-circle-o"></i>Users', 'url' => ['users/index']],
//                    ]],


            ],
            //'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
            'submenuTemplate' => "\n<ul class='treeview-menu' role='menu'>\n{items}\n</ul>\n",
        ]);
        ?>
    </section>
    <!-- /.sidebar -->
</aside>
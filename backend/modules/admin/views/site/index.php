      <!-- Main content -->
<?php

use common\models\Billing;
use common\models\Portal;
use common\models\User;
use yii\helpers\Html;
?>
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
<!--        <div class="col-lg-3 col-xs-6">
           small box 
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>150</h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>-->
 <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
                  <?php $user = User::find()->where('isDeleted =0 and is_admin!=1')->count(); ?>
              <h3><?= $user; ?></h3>

              <p>Tech Management</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
              <?php echo Html::a('More info', ['/admin/tech'], ["class" => 'small-box-footer']); ?>
            <!--<a href="/tech" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
          </div>
        </div>
        <!-- ./col -->
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
           <!--small box--> 
          <div class="small-box bg-green">
            <div class="inner">
                 <?php $billing = Billing::find()->where('deleted_at =0')->count(); ?>
              <h3><?= $billing; ?><sup style="font-size: 20px"></sup></h3>

              <p>Billing Information</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
                <?php echo Html::a('More info', ['/admin/billing'], ["class" => 'small-box-footer']); ?>
            <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
          </div>
        </div>
       
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-aqua">
            <div class="inner">
                 <?php $portal =Portal::find()->where('deleted_at =0')->count(); ?>
              <h3><?= $portal; ?></h3>

              <p>Portal Management</p>
            </div>
            <div class="icon">
              <i class="fa fa-comments-o"></i>
            </div>
              <?php echo Html::a('More info', ['/admin/portal'], ["class" => 'small-box-footer']); ?>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
     
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->

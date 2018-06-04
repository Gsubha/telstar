      <!-- Main content -->
<?php

use common\models\Billing;
use common\models\Portal;
use common\models\User;
?>
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
            
      </div>
        <?php $user=User::find()->where(['id'=>Yii::$app->user->id])->one();
               $billing = Billing::find()->where('deleted_at =0')->andWhere(['techid' => $user->username])->count(); 
                $type= Billing::typeList();
                $staticstart = date('Y-m-d', strtotime('last Sunday'));
                $staticfinish = date('Y-m-d', strtotime('next Saturday'));
                $portal =Portal::find()->where('deleted_at =0')->andWhere('status =10')->orderBy(['created_at' => SORT_DESC])->all(); 
               ?>
      
       <div class="row" >
        <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black" style="background: url('/backend/web/themes/tech/img/photo4.jpg') center center;">
              <div class="widget-user-image">
                <img class="img-circle" src="/backend/web/themes/tech/img/avatar.png" alt="User Avatar">
              </div>
              <h3 class="widget-user-username"><?php echo ($user->firstname) ? $user->firstname.' '.$user->lastname : "Tech ID- ".$user->username?></h3>
              <h5 class="widget-user-desc">Current Week Billing Details</h5>
                <h4 class="widget-user-desc">From - <?=  Billing::dateFormat($staticstart) ?> </h4>
                     <h4 class="widget-user-desc">  Until - <?=   Billing::dateFormat($staticfinish) ?> </h4>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
                  
                  <?php
                  $total=0;
                  foreach ($type as $key => $value) {
                      
                   $accessbilling = Billing::accessBillingCount($key);
                   $total+=$accessbilling;
                   ?>
               <li><a href="#"><?= $value?> <span class="pull-right badge bg-blue">$<?= empty($accessbilling)? 0 :$accessbilling ?></span></a></li>    
                   <?php } ?>
                  
                <li><a href="#"><b>Total</b> <span class="pull-right badge bg-red">$<?= $total ?></span></a></li>
              </ul>
            </div>
          </div>
          
        </div>
      
        <div class="col-md-8">
          <!-- DIRECT CHAT SUCCESS -->
          <div class="box box-success direct-chat direct-chat-success">
            <div class="box-header with-border">
              <h3 class="box-title">Portal Message</h3>

              <div class="box-tools pull-right">
                   <span data-toggle="tooltip" title="3 New Messages" class="badge bg-red">From Admin</span>
                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle">
                  <i class="fa fa-comments"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages">
                <!-- Message. Default to the left -->
                <div class="direct-chat-msg">
                    <?php foreach ($portal as $key => $value) { ?>
                  <div class="direct-chat-info clearfix">
                    <!--<span class="direct-chat-name pull-left">Admin</span>-->
                      <div class="col-lg-12 col-md-12">&nbsp;</div>
                    <span class="direct-chat-timestamp pull-right"><?php echo $value->created_at ?></span>
                  </div>
                  <!-- /.direct-chat-info -->
                  <img class="direct-chat-img" src="/backend/web/themes/tech/img/avatar.png" alt="Message User Image"><!-- /.direct-chat-img -->
                  <div class="direct-chat-text">
                    <?php echo $value->portal_message ?>
                  </div>
                    <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div></div>
    </section>

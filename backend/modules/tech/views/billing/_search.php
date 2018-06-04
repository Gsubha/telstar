<?php

use common\models\Billing;
use common\models\DlStudentSearch;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlStudentSearch */
/* @var $form ActiveForm */
?>
<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
//            'fieldConfig' => [
//                    'template' => "{label}<div class=\"col-sm-4\">{input}</div>",
//                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
//                ],
        ]);
?>
<div class="col-lg-12 col-md-12">
    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-search"></i>  Search
                </h3>
                <div class="clearfix"></div>
            </div>
            <section class="content boxheight">
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <?php echo $form->field($model, 'keyword'); ?> 
                           
                        </div>
                       
                    </div>  
<!--                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <?php // echo $form->field($model, 'last_name'); ?>                  
                        </div>
                    </div>  -->
<!--                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <?php // echo $form->field($model, 'student_dob')->textInput(['class' => 'form-control datepicker']); ?>                  
                        </div>
                    </div> -->
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <?php echo $form->field($model, 'started_at')->textInput(['class' => 'form-control datepicker'])->label('<i class="fa fa-calendar"></i>Start Date'); ?>                  
                        </div>
                    </div> 
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">

                            <?php echo $form->field($model, 'ended_at')->textInput(['class' => 'form-control datepicker'])->label('<i class="fa fa-calendar"></i>End Date'); ?>                  
                        </div>
                    </div> 

                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                             <?php $type= Billing::typeList() ?>
                          <?php echo $form->field($model, 'type')->dropDownList($type,['class' => 'form-control', 'prompt' => '--- Select Type ---']) ?>             
                        </div>
                    </div>  
                    <!--<div class="col-lg-4 col-md-4">-->
                        <!--<div class="form-group">-->
                            <?php
                            //echo $form->field($model, 'student.studentProfile.dob');
//                            echo $form->field($model, 'searchstatus')->dropdownList(['1' => 'Not Assigned', '2' => 'Assigned (need to add remaining hours )', '5' => 'Assigned (no remaining hours )', '3' => 'Registered (paid)', '4' => 'Registered (not paid)'], ['prompt' => 'All']);
                            ?>                  
                        <!--</div>-->
                    <!--</div>-->  

                    <div class="col-lg-1 col-md-1 reset1">
                        <div class="form-group">
                            <label>&nbsp;</label>                        
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary ']) ?>&nbsp;
                        </div></div>
                         <div class="col-lg-1 col-md-1 reset1">
                        <div class="form-group">
                            <label>&nbsp;</label>                        
                      <?= Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
                        </div>
                    </div>
  
                </div>
                <p><b>*Search By Keyword ( Work Code, Work Order )</b></p>
            </section>
        </div>
    </div>
</div>

<?php
ActiveForm::end();
$script = <<< JS
$(document).ready(function(){
    //Date picker
    $('.datepicker').datepicker({
//         dateFormat: 'yy-mm-dd' ,
      autoclose: true
    });
}); 
JS;
$this->registerJs($script, View::POS_END);
?>

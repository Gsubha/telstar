<?php

use common\models\Billing;
use common\models\DlStudentSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlStudentSearch */
/* @var $form ActiveForm */
?>
<?php
$form = ActiveForm::begin([
            'action' => ['tech-overview'],
            'method' => 'get',
        ]);
$billing_obj = new Billing();
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
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <?php
                            echo $form->field($model, 'techid')->dropDownList(ArrayHelper::map($billing_obj->techIds, 'techid', 'techid'), ['class' => 'form-control', 'prompt' => '--- All ---']);
                            ?>             
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <?php
                            if (isset($_GET['BillingSearch']['vendor'])) {
                                $model->vendor = $_GET['BillingSearch']['vendor'];
                            }
                            echo $form->field($model, 'vendor')->dropDownList(ArrayHelper::map($billing_obj->vendors, 'id', 'vendor_type'), ['class' => 'form-control', 'prompt' => '--- All ---']);
                            ?>             
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <?php
                            if (isset($_GET['BillingSearch']['location'])) {
                                $model->location = $_GET['BillingSearch']['location'];
                            }
                            echo $form->field($model, 'location')->dropDownList(ArrayHelper::map($billing_obj->locations, 'id', 'location'), ['class' => 'form-control', 'prompt' => '--- All ---']);
                            ?>             
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <?php echo $form->field($model, 'started_at')->textInput(['class' => 'form-control datepicker'])->label('<i class="fa fa-calendar"></i> Start Date'); ?>                  
                        </div>
                    </div> 
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">

                            <?php echo $form->field($model, 'ended_at')->textInput(['class' => 'form-control datepicker'])->label('<i class="fa fa-calendar"></i> End Date'); ?>                  
                        </div>
                    </div> 
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <div class="col-lg-4 col-md-4 reset1" style="margin-top:25px;"><?= Html::submitButton('Search', ['class' => 'btn btn-primary ']) ?></div>
                            <div class="col-lg-4 col-md-4 reset1" style="margin-top:25px;"><?= Html::a('Reset', ['tech-overview'], ['class' => 'btn btn-default']) ?></div>    
                        </div>
                    </div> 

                </div>
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

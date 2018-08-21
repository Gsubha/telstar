<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
?>
<?php
$form = ActiveForm::begin([
            'action' => ['techdeductions'],
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
                            <?php
                            if (isset($_GET['TechDeductionsSearch']['techid'])) {
                                $model->techid = $_GET['TechDeductionsSearch']['techid'];
                            }
                            echo $form->field($model, 'techid')->dropDownList(ArrayHelper::map($model->techIds, 'user_id', 'techid'), ['class' => 'form-control', 'prompt' => '--- All ---']);
                            ?>    
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <?php 
                             if (isset($_GET['TechDeductionsSearch']['startdate'])) {
                                $model->startdate = $_GET['TechDeductionsSearch']['startdate'];
                            }
                            echo $form->field($model, 'startdate')->textInput(['class' => 'form-control datepicker'])->label('<i class="fa fa-calendar"></i> Start Date'); ?>                  
                        </div>
                    </div> 
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">

                            <?php 
                            if (isset($_GET['TechDeductionsSearch']['enddate'])) {
                                $model->enddate = $_GET['TechDeductionsSearch']['enddate'];
                            }
                            echo $form->field($model, 'enddate')->textInput(['class' => 'form-control datepicker'])->label('<i class="fa fa-calendar"></i> End Date'); ?>                  
                        </div>
                    </div> 
                    <div class="col-lg-1 col-md-1 reset1"><?= Html::submitButton('Search', ['class' => 'btn btn-primary ']) ?></div>
                    <div class="col-lg-1 col-md-1 reset1"><?= Html::a('Reset', ['techdeductions'], ['class' => 'btn btn-default']) ?></div>    
                </div>
            </section>
        </div>
    </div>
</div>

<?php
ActiveForm::end();
$script = <<< JS
$(document).ready(function(){
        var height = $('.content-wrapper:visible').css('height');
        $(".customsidebar").height(height);
    //Date picker
    $('.datepicker').datepicker({
//         dateFormat: 'yy-mm-dd' ,
      autoclose: true
    });
}); 
JS;
$this->registerJs($script, View::POS_END);
?>

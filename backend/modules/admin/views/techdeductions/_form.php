<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Techdeductions */
/* @var $form yii\widgets\ActiveForm */
?>

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <?php
                $form = ActiveForm::begin(['id' => 'active-form',
                        'options' => [
                            'class' => 'form-horizontal',
                        ],
                        'fieldConfig' => [
                            'template' => "{label}<div class=\"col-sm-8\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                            'labelOptions' => ['class' => 'col-sm-3 control-label'],
                        ],
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => true,
                    ]
                );
                ?>
                <div class="box-body">
                    <div class="form-group" style="display: none;">
                        <div class="col-md-8">
                            <?php
                            if(isset($_GET['tech_id'])){
                                $model->user_id = $_GET['tech_id'];
                            }
                            ?>
                            <?= $form->field($model, 'user_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\User::find()->where(['not', ['techid' => null]])->all(), 'id', 'techid'), ['class' => 'form-control', 'prompt' => '--- Select Tech ---'])->label('Tech ID*'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8">
                            <?= $form->field($model, 'category')->dropDownList(\common\models\TechDeductions::$categories, ['class' => 'form-control', 'prompt' => '--- Select Category ---'])->label('Category*'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8">
                            <?= $form->field($model, 'deduction_info')->textInput()->label('Deduction Info'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8">
                            <?php
                            if(@$model->deduction_date) {
                                $model->deduction_date = date('m/d/Y', strtotime($model->deduction_date));
                            }
                            ?>
                            <?= $form->field($model, 'deduction_date')->textInput(['class' => 'form-control datepicker'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Deducation Date*'); ?>
                        </div>
                    </div>
                    <div id="periodic_dates" style="display: none;">
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if(@$model->startdate) {
                                    $model->startdate = date('m/d/Y', strtotime($model->startdate));
                                }
                                ?>
                                <?= $form->field($model, 'startdate')->textInput(['class' => 'form-control datepicker'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Start Date*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if(@$model->enddate) {
                                    $model->enddate = date('m/d/Y', strtotime($model->enddate));
                                }
                                ?>
                                <?= $form->field($model, 'enddate')->textInput(['class' => 'form-control datepicker'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> End Date*'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group dqty" style="display: none;">
                        <div class="col-md-8">
                            <?= $form->field($model, 'qty')->textInput(['maxlength' => true])->label('Qty'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8">
                            <?= $form->field($model, 'total')->textInput(['maxlength' => true])->label('Amount*'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8">
                            <?= $form->field($model, 'description')->textarea()->label('Comments') ?>
                        </div>
                    </div>
                    <div class="box-footer">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>
<?php
$callback = Yii::$app->urlManager->createUrl(['admin/techdeductions/getprice']);
$script = <<< JS
$(document).ready(function(){
    var category = '{$model->category}';
    showFields(category);      
      
    //Date picker
    $('.datepicker').datepicker({
//         dateFormat: 'yy-mm-dd' ,
      autoclose: true
    });          
    
    $("#techdeductions-category").on("change", function(){
        var Category = $("#techdeductions-category").val();
        showFields(Category);
    });
    
    function showFields(Category){       
        if(Category == "ongoing"){
            $(".dqty").show();
            $("#periodic_dates").hide();
        }else if(Category == "onetime"){
            $(".dqty").hide();    
            $(".periodic_dates").hide();
        }else if(Category == "periodic"){
            $(".dqty").show();
            $("#periodic_dates").show();
        }
    }
    
//    $("#techdeductions-deduction_id").on("change", function(){
//        var qty = $("#techdeductions-qty").val();   
//        var deduction_id = $(this).val();       
//        if(deduction_id > 0){
//            $.ajax({
//                method: 'POST',
//                url: '{$callback}',
//                data: 'deduction_id='+$(this).val(),
//                success: function (price) {                                
//                    $("#priceperunit").show();
//                    $("#unitprice").html(price);
//                    $("#unitpricehidden").val(price);
//                    calcualatetotal(qty,price); 
//                }
//            });
//        }else{
//             $("#priceperunit").hide();
//             $("#unitprice").html("0.00");
//             $("#unitpricehidden").val("0.00");
//             $("#techdeductions-total").val( "0.00" );
//        }     
//    });
//    
//    $("#techdeductions-qty").on("keyup", function(){
//        var qty = $(this).val();   
//        var unitprice = $("#unitpricehidden").val();   
//        calcualatetotal(qty,unitprice);
//        return false;
//    });
}); 

// function calcualatetotal(qty,unitprice){    
//     if(qty && unitprice){
//         var total = parseFloat(qty) * parseFloat(unitprice);
//         $("#techdeductions-total").val( parseFloat(total));     
//     }else{
//         $("#techdeductions-total").val( "0.00" );
//     }
// }
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>



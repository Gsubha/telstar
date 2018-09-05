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
                                'enctype' => 'multipart/form-data',
                            ],
                            'fieldConfig' => [
                                'template' => "{label}<div class=\"col-sm-8\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                            ],
                            'enableClientValidation' => true,
                                ]
                );
                ?>
                <div class="box-body">
                    <div class="form-group" style="display: none;">
                        <div class="col-md-8">
                            <?php
                            if (isset($_GET['tech_id'])) {
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
                    <div class="form-group ongoing_divs hideshow" id='ongoing_type_div'>
                        <div class="col-md-8">
                            <?php
                            if (@$model->deduction_info) {
                                $model->deduction_info = $model->deduction_info;
                            } else {
                                $model->deduction_info = "";
                            }
                            ?>
                            <?= $form->field($model, 'deduction_info')->dropDownList(\common\models\TechDeductions::$ongoing_categories, ['class' => 'form-control', 'prompt' => '--- Select Type ---'])->label('Deduction Type*'); ?>
                        </div>
                    </div>
                    <div class="meter_lease hideshow ongoing_divs">
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'serial_num')->textInput()->label('Serial Number*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->deduction_date) {
                                    $model->issue_date = date('m/d/Y', strtotime($model->deduction_date));
                                } else {
                                    $model->issue_date = date('m/d/Y');
                                }
                                ?>
                                <?= $form->field($model, 'issue_date')->textInput(['class' => 'form-control datepicker'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Issue Date*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'total')->textInput(['maxlength' => true])->label('Amount*'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="van_lease hideshow ongoing_divs">
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->yes_or_no) {
                                    $model->van_yes = 1;
                                } else {
                                    $model->van_yes = 0;
                                }
                                echo $form->field($model, 'van_yes')->checkbox(['label' => ''])->label('Yes');
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'vin')->textInput()->label('Vin#*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->deduction_date) {
                                    $model->van_deduction_date = date('m/d/Y', strtotime($model->deduction_date));
                                } else {
                                    $model->van_deduction_date = date('m/d/Y');
                                }
                                ?>
                                <?= $form->field($model, 'van_deduction_date')->textInput(['class' => 'form-control datepicker', 'id' => 'van_deduction_date'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Date*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->total) {
                                    $model->van_amount = $model->total;
                                } else {
                                    $model->van_amount = "";
                                }
                                ?>
                                <?= $form->field($model, 'van_amount')->textInput(['maxlength' => true, 'id' => 'van_amount'])->label('Amount*'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="wcgl_lease hideshow ongoing_divs">
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->yes_or_no) {
                                    $model->wcgl_yes = 1;
                                } else {
                                    $model->wcgl_yes = 0;
                                }
                                echo $form->field($model, 'wcgl_yes')->checkbox(['label' => ''])->label('Yes');
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'percentage')->dropDownList(\common\models\TechDeductions::$wcgl_percentages, ['class' => 'form-control', 'prompt' => '--- Select Percentage ---'])->label('Percentage*'); ?>
                            </div>
                        </div>
                    </div>

                    <?php /* One time deduction form - Start */ ?>
                    <div class="onetime hideshow">
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->deduction_info) {
                                    $model->onetime_deduction_type = $model->deduction_info;
                                } else {
                                    $model->onetime_deduction_type = "";
                                }
                                ?>
                                <?= $form->field($model, 'onetime_deduction_type')->textInput(['id=>onetime_deduction_type'])->label('Deduction Type*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->deduction_date) {
                                    $model->deduction_date = date('m/d/Y', strtotime($model->deduction_date));
                                } else {
                                    $model->deduction_date = date('m/d/Y');
                                }
                                ?>
                                <?= $form->field($model, 'deduction_date')->textInput(['class' => 'form-control datepicker', 'id' => 'onetime_deduction_date'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Deduction Date*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'work_order')->textInput()->label('Work Order*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->total) {
                                    $model->onetime_amt = $model->total;
                                } else {
                                    $model->onetime_amt = "";
                                }
                                ?>
                                <?= $form->field($model, 'onetime_amt')->textInput(['maxlength' => true, 'id' => 'onetime_amount'])->label('Amount*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'description')->textarea(['id' => 'onetime_comment'])->label('Comment*'); ?>
                            </div>
                        </div>
                    </div>
                    <?php /* One time deduction form - End */ ?>
                    <?php /* Installment Deduction form - Start  */ ?>
                    <div class="form-group installment hideshow">
                        <div class="col-md-8">
                            <?php
                            if (@$model->deduction_info) {
                                $model->installment_type = $model->deduction_info;
                            } else {
                                $model->installment_type = "";
                            }
                            ?>
                            <?= $form->field($model, 'installment_type')->dropDownList(\common\models\TechDeductions::$installment_categories, ['class' => 'form-control', 'prompt' => '--- Select Type ---'])->label('Installment Type*'); ?>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->total) {
                                    $model->installment_amount = $model->total;
                                } else {
                                    $model->installment_amount = "";
                                }
                                ?>
                                <?= $form->field($model, 'installment_amount')->textInput(['maxlength' => true, 'id' => 'installment_amount'])->label('Amount*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'num_installment')->textInput(['maxlength' => true])->label('Number of Installments*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->description) {
                                    $model->installment_comment = $model->description;
                                } else {
                                    $model->installment_comment = "";
                                }
                                ?>
                                <?= $form->field($model, 'installment_comment')->textarea()->label('Comment*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->startdate) {
                                    $model->startdate = date('m/d/Y', strtotime($model->startdate));
                                } 
                                ?>
                                <?= $form->field($model, 'startdate')->textInput(['class' => 'form-control hideweekdays', 'id' => 'inst_startweek_date'])->label('<i class="fa fa-calendar"></i> Start Week Date*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->enddate) {
                                    $model->enddate = date('m/d/Y', strtotime($model->enddate));
                                } 
                                ?>
                                <?= $form->field($model, 'enddate')->textInput(['class' => 'form-control hideweekdays', 'id' => 'inst_endweek_date'])->label('<i class="fa fa-calendar"></i> End Week Date*'); ?>
                            </div>
                        </div>
                    </div>
                    <?php /* Installment Deduction form - End */ ?>

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
$callback = Yii::$app->urlManager->createUrl(['admin/techdeductions/get_ongoing_types']);
$script = <<< JS
$(document).ready(function(){
    $(".hideshow").hide();
    var category = '{$model->category}';
    showFields(category);
    
    $('.datepicker').datepicker({
      //dateFormat: 'yy-mm-dd' ,
      autoclose: true
    });
    
   $('.hideweekdays').datepicker({
    daysOfWeekDisabled: [1,2,3,4,5],
   }); 
   
    
    $("#techdeductions-category").on("change", function(){
        $(".hideshow").hide();
        var Category = $("#techdeductions-category").val();
        showFields(Category);
    });
    
    $("#techdeductions-deduction_info").on("change",function(){
        $(".hideshow").hide();
        var ongoingType=$('#techdeductions-deduction_info').val();
        showOngoingType(ongoingType);
    });
    
    
    
    function showFields(Category){ 
        switch(Category)
        {
            case "ongoing":
                var deduction_type = '{$model->deduction_info}';
                $('#techdeductions-deduction_info').prop('selectedIndex',0);
                if(deduction_type!=''){
                    $("#techdeductions-deduction_info").val(deduction_type);
                    showOngoingType(deduction_type);
                }
                $("#ongoing_type_div").show();
            break;
            
            case "onetime":
                $(".onetime").show();
            break;
                
            case "installment":
                $(".installment").show();
            break;
    
            default:
                $(".hideshow").hide();
            break;
        }
    }
    
    function showOngoingType(ongoingType)
    {
        $("#ongoing_type_div").show();
        switch(ongoingType)
        {
            case "Meter":
            $(".meter_lease").show();
            break;
    
            case "Truck":
            $(".van_lease").show();
            break;
    
            case "WC/GL":
            $(".wcgl_lease").show();
            break;
        }
    }
    
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>



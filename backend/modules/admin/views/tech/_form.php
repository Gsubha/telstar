<?php

use common\models\Billing;
use common\models\Location;
use common\models\TechOfficial;
use common\models\TechProfile;
use common\models\User;
use common\models\Vendor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model User */
/* @var $form ActiveForm */


$querys_new = Location::find()->andWhere('status = :status and isDeleted = :delete', [':status' => 10, ':delete' => 0])->all();
$location = ArrayHelper::map($querys_new, "id", function ($model, $defaultValue) {
    $sinfo = $model->location;

    return $sinfo;
});

$vendor = '';
$querys_new1 = Vendor::find()->andWhere('status = :status and deleted_at = :delete', [':status' => 10, ':delete' => 0])->all();
$vendor = ArrayHelper::map($querys_new1, "id", function ($model, $defaultValue) {
    $sinfo = $model->vendor_type;

    return $sinfo;
});
if ($model->isNewRecord)
    $vendor += array('-1' => 'other');
?>

    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"><b>Technical Information</b></a></li>
                        <li><a href="#tab_2" data-toggle="tab"><b>Vehicle Information</b></a></li>
                        <li><a href="#tab_3" data-toggle="tab"><b>Official Information</b></a></li>
                    </ul>
                    <?php
                    $form = ActiveForm::begin(['id' => 'active-form',
                            'options' => [
                                'class' => 'form-horizontal',
//                                'enctype' => 'multipart/form-data',
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

                    <div class="tab-content">

                        <div class="tab-pane active" id="tab_1">
                            <div class="box box-primary">


                                <div class="box-body">

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'techid')->textInput(['maxlength' => true, 'readonly' => !$model->isNewRecord])->label('Tech ID*'); ?>
                                        </div>
                                        <div class="col-md-6">

                                            <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <?php
                                        if (!$model->isNewRecord)
                                            $model->password_hash = '';
                                        ?>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true])->label('Password*') ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">

                                            <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('User Name*') ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($tech, 'work_status')->dropDownList(TechProfile::$workstatusList, ['class' => 'form-control', 'prompt' => '--- Select Work Status ---'])->label('Work Status') ?>
                                        </div>

                                    </div>
                                    <?php //   if($tech->isNewRecord){ ?>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?= $form->field($tech, 'vendor_id')->dropDownList($vendor, ['class' => 'form-control', 'prompt' => '--- Select Vendor ---'])->label('Vendor') ?>
                                            <div class="other-field">
                                                <?= $form->field($tech, 'other')->textInput(['maxlength' => true])->label('') ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($tech, 'location_id')->dropDownList($location, ['class' => 'form-control', 'prompt' => '--- Select Location ---'])->label('Location') ?>
                                        </div>

                                    </div>
                                    <?php // } ?>


                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?Php
                                            if (isset($tech->dob))
                                                $tech->dob = Billing::dateFormat($tech->dob);
                                            ?>
                                            <?= $form->field($tech, 'dob')->textInput(['class' => 'form-control datepicker'], ['maxlength' => true])->label('DOB')->label('<i class="fa fa-calendar"></i> DOB') ?>

                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'addr')->textarea(['maxlength' => true]) ?>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'city')->textInput(['maxlength' => true]); ?>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'email')->textInput(['maxlength' => true]); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>
                                        </div>


                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?php
                                            if ($model->isNewRecord)
                                                $model->status = '10';
                                            ?>
                                            <?= $form->field($model, 'status')->radioList(['10' => 'Enabled', '0' => 'Disabled']) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <div class="box box-primary">
                                <div class="box-body">

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?= $form->field($tech_vehicle, 'license_plate')->textInput(['maxlength' => true]); ?>
                                        </div>
                                        <div class="col-md-6">

                                            <?= $form->field($tech_vehicle, 'state')->textInput(['maxlength' => true]) ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?php /* echo $form->field($tech_vehicle, 'registration')->textInput(['maxlength' => true]) */ ?>
                                            <?= $form->field($tech_vehicle, 'reg_exp')->textInput(['maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-6">
                                           <?= $form->field($tech_vehicle, 'insurance_company')->textInput(['maxlength' => true]) ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?= $form->field($tech_vehicle, 'insurance_exp')->textInput(['maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-6">
                                           <?= $form->field($tech_vehicle, 'driver_license')->textInput(['maxlength' => true]) ?> 
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?= $form->field($tech_vehicle, 'issuing_state')->textInput(['maxlength' => true]) ?> 
                                        </div>
                                        <div class="col-md-6">
                                           
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_3">
                            <div class="box box-primary">
                                <div class="box-body">

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?Php
                                            if (isset($tech_offcl->hire_date))
                                                $tech_offcl->hire_date = Billing::dateFormat($tech_offcl->hire_date);
                                            ?>
                                            <?= $form->field($tech_offcl, 'hire_date')->textInput(['class' => 'form-control datepicker'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Hire Date'); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($tech_offcl, 'job_desc')->dropDownList(TechProfile::$jobdescList, ['class' => 'form-control', 'prompt' => '--- Select Job Description ---'])->label('Position Of Job Description') ?>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?= $form->field($tech_offcl, 'vid')->textInput(['maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($tech_offcl, 'pid')->textInput(['maxlength' => true]) ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?Php
                                            if (isset($tech_offcl->badge_exp_date))
                                                $tech_offcl->badge_exp_date = Billing::dateFormat($tech_offcl->badge_exp_date);
                                            ?>
                                            <?= $form->field($tech_offcl, 'badge_exp_date')->textInput(['class' => 'form-control datepicker'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Badge Expiry Date') ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?Php
                                            if (isset($tech_offcl->hire_date))
                                                $tech_offcl->last_background_check = Billing::dateFormat($tech_offcl->last_background_check);
                                            ?>
                                            <?= $form->field($tech_offcl, 'last_background_check')->textInput(['class' => 'form-control datepicker'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Last Background Check'); ?>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?Php
                                            if (isset($tech_offcl->term_date))
                                                $tech_offcl->term_date = Billing::dateFormat($tech_offcl->term_date);
                                            ?>
                                            <?= $form->field($tech_offcl, 'term_date')->textInput(['class' => 'form-control datepicker'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Term Date') ?>
                                        </div>

                                        <div class="col-md-6">
                                            <?= $form->field($tech_offcl, 'rate_code_type')->radioList(['In' => 'In House', 'Cp' => 'Corporate']) ?>
                                            <div class="inhouse">
                                                <?php $tech_offcl->inhouse = $tech_offcl->rate_code_val; ?>
                                                <?= $form->field($tech_offcl, 'inhouse')->dropDownList(TechOfficial::$inhouseList, ['class' => 'form-control', 'prompt' => '--- Select In House Option ---'])->label('') ?>
                                            </div>
                                            <div class="corporate">
                                                <?php $tech_offcl->corporate = $tech_offcl->rate_code_val; ?>
                                                <?= $form->field($tech_offcl, 'corporate')->dropDownList(TechOfficial::$corporateList, ['class' => 'form-control', 'prompt' => '--- Select Corporate Option ---'])->label('') ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="box-footer">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-success save_other']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$other = Yii::$app->urlManager->createUrl('/admin/tech/getothers');

$script = <<< JS
$(document).ready(function(){
    //Date picker
    $('.datepicker').datepicker({
//         dateFormat: 'yy-mm-dd' ,
      autoclose: true
    });
        
        //For chossing vendor "Other" field
        
         $(".other-field").hide();
        var id=$("#techprofile-vendor_id option:selected").text();
         if(id=='other'){
        $(".other-field").show();
        }
         $("#techprofile-vendor_id").change(function(e){
         var id=$("#techprofile-vendor_id option:selected").text();
         var other_field_val=$("#techprofile-other").val();
        if(id=='other'){
        $(".other-field").show();
        }else{
          $(".other-field").hide();
        }
        
        });
        
        //For chossing rate code field
        
         $(".inhouse").hide();
         $(".corporate").hide();
        var id=$("input[name='TechOfficial[rate_code_type]']:checked").val();
         if(id=='In'){
        $(".inhouse").show();
        }
        if(id=='Cp'){
          $(".corporate").show();
            }
         $("#techofficial-rate_code_type").change(function(){
      
         var id= $("input[name='TechOfficial[rate_code_type]']:checked").val();
        if(id=='In'){
        $(".inhouse").show();
         $(".corporate").hide();
        }
        if(id=='Cp'){
          $(".corporate").show();
        $(".inhouse").hide();
        }
        
        });
        
}); 
JS;
$this->registerJs($script, View::POS_END);



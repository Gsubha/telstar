<?php

use common\models\User;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model User */
/* @var $form ActiveForm */
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
                <div class="box-body">
                    
                    <div class="form-group">
                         <div class="col-md-6">
                        <?= $form->field($model, 'techid')->textInput(['maxlength' => true,'readonly'=> !$model->isNewRecord])->label('Tech ID*'); ?>
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
                    </div></div>
                    
                    <div class="form-group">
                         <div class="col-md-6">
                               <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('User Name*') ?>
                        
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
                    
<!--                    <div class="form-group">
                         <div class="col-md-6">
                           <? // $form->field($model, 'status')->radioList(['10' => 'In House', '0' => 'Corporate'])->label("Rate Code") ?> 
                              <? // $form->field($model, 'type')->dropDownList(['1'=>'50%','2'=>'60%'],['class' => 'form-control', 'prompt' => '--- Select Type ---'])->label("") ?>             
                    </div>
                         
                       </div>-->

                    <div class="box-footer">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div></div></section>
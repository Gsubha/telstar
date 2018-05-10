<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
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
                                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            ],
                                ]
                );
                ?>
                <div class="box-body">
                    
                    <div class="form-group">
                         <div class="col-md-6">
                        <?= $form->field($model, 'techid')->textInput(['maxlength' => true])->label('Tech ID*'); ?>
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
                               <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('UserName*') ?>
                        
                    </div>
                     <div class="col-md-6">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    </div></div>
                    
                    <div class="form-group">
                        <div class="col-md-6">
                        <?= $form->field($model, 'addr')->textarea(['maxlength' => true]) ?>
                    </div>
                      <div class="col-md-6">
                        <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>
                    </div></div>

                    <div class="form-group">
                        <div class="col-md-6">
                        <?php
                        if ($model->isNewRecord)
                            $model->status = '10';
                        ?>
                        <?= $form->field($model, 'status')->radioList(['10' => 'Enabled', '0' => 'Disabled']) ?>
                    </div></div>

                    <div class="box-footer">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div></div></section>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Location */
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
                         <div class="col-md-8">
    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
</div>
                     <div class="col-md-8">
                        <?php
                        if ($model->isNewRecord)
                            $model->status = '10';
                        ?>
                        <?= $form->field($model, 'status')->radioList(['10' => 'Enabled', '0' => 'Disabled']) ?>
                    </div></div>

    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

  <?php ActiveForm::end(); ?>

            </div>
        </div></div></section>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\common\models\Deductions */
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
                    <div class="form-group">
                        <div class="col-md-8">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Name*'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8">
                            <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8">
                            <?= $form->field($model, 'price')->textInput(['maxlength' => true])->label('Price* (per unit)'); ?>
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

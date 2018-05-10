<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Billing */
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
                                'template' => "{label}<div class=\"col-sm-5\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            ],
                                ]
                );
                ?>
<div class="box-body">
     <div class="form-group">
    <?= $form->field($model, 'type')->dropDownList(['access_point_details'=>'Access Point Details','all_digital_details' => 'All Digital Details', 'billing_details' => 'Billing Details']) ?>
</div>
    <div class="form-group">
    <?= $form->field($model, 'upload')->fileInput() ?>
        </div>
   
    <div class="box-footer">
        <?= Html::submitButton('Import', ['class' => 'btn btn-success']) ?>
    </div>
 </div>
    <?php ActiveForm::end(); ?>

  </div>
        </div></div></section>

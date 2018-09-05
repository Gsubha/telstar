<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Import Tech Deductions';
$this->params['breadcrumbs'][] = ['label' => 'Tech', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <!--<h1><? // Html::encode($this->title)  ?></h1>-->
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
                            <?= $form->field($model, 'category')->dropDownList(\common\models\TechDeductions::$categories, ['class' => 'form-control','prompt' => 'Select Import Type']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'file')->fileInput() ?>

                            <p>
                            <div style="padding:10px;"><h3 style="color:red">Hints:</h3></div>
                            <ul>
                                <li> Please use the given excel-sheet format ( Example:<a href="download?url=web/uploads/sample/Onetime.xls"> One Time Deduction</a> , <a href="download?url=web/uploads/sample/Ongoing.xls"> On Going Deduction</a>, <a href="download?url=web/uploads/sample/Instalment.xls"> Installment Deduction</a> )</li>
                                <li> Please fill the list of mandatory fields for each schedule. (Tech ID, Category, Deduction Type, Amount, Deduction Date)</li>
                                <li> Please fill the date format using this syntax MM/DD/YYYY. Example: 05/15/2018 . Date format fields in the excel-sheet ( Deduction Date, Start Date, End Date )  </li>
                                <li> Please fill the <strong>Category</strong> column using following values,
                                    <br/>
                                    - For OnGoing deductions use value <strong>ongoing</strong>.
                                    <br/>
                                    - For OneTime deductions use value <strong>onetime</strong>.
                                    <br/>
                                    - For Installment deductions use value <strong>installment</strong>.
                                </li>
                                <li> Please fill the <strong>Deduction Type</strong> column using following values,
                                   <br/>
                                    - For Meter Lease, use value <strong>Meter</strong>. (Required: Serial Number).
                                    <br/>
                                    - For Van Lease, use value <strong>Truck</strong>. (Required: Yes/No, Vin#).
                                    <br/>
                                    - For WC/GL, use value <strong>WC/GL</strong>. (Required: Yes/No, Percentage).
                                    <br>
                                    - For <b>Percentage</b>, use any one value from <b>5,8,10,12,15</b>.
                                </li>
                                <!--<li><i> <strong>Note: </strong>Start Date and End Date Required for periodic category </i></li>-->

                            </ul>
                            </p>

                        </div>

                        <div class="box-footer">
                            <?= Html::submitButton('Import', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </section>


</div>

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
                            <?= $form->field($model, 'file')->fileInput() ?>

                            <p>
                            <div style="padding:10px;"><h3 style="color:RED">Hints:</h3></div>
                            <ul>
                                <li> Please use the given excelsheet format ( Example:<a href="download?url=web/uploads/sample/tech_deduction1.xlsx"> Tech Deduction Informations</a> )</li>
                                <li> Please fill the list of mandatory fields for each schedule. (Tech ID, Category, Amount)</li>
                                <li> Please fill the date format using this syntax MM/DD/YYYY. Example: 05/15/2018 . Date format fields in the excelsheet ( Deduction Date , Deduction Start Date, Deduction End Date)  </li>
                                <li> Please fill the <strong>Category</strong> column using following values,
                                    <br/>
                                    For ongoing deductions use value <strong>"On-going deduction"</strong>.
                                    <br/>
                                    For one time deductions use value <strong>"One time deduction"</strong>.
                                    <br/>
                                    For periodic deductions use value <strong>"Periodic deduction"</strong>.
                                </li>
                                <!--(III) Please fill the START_TIME and END_TIME using this syntax. Example: 11:00 am (Need space between time and the meridians)Choose your respective affiliate and instructor code.<br>-->
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

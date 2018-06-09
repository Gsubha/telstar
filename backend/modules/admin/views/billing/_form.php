<?php

use common\models\Billing;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Billing */
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
                        <?= $form->field($model, 'type')->dropDownList(Billing::$typeList, ['class' => 'form-control', 'prompt' => '--- Select Type ---']) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'file')->fileInput() ?>

                        <p>
                        <div style="padding:10px;"><h6 style="color:RED">Hints:</h6></div>
                        <ul>
                            <li> Please use the given excelsheet format ( Example:<a
                                        href="download?url=web/uploads/sample/all_digital_details.xls"> All Digital
                                    Details</a> ,<a href="download?url=web/uploads/sample/access_point_details.xls">
                                    Access Point Details</a> ,<a
                                        href="download?url=web/uploads/sample/billing_details.xls"> Billing Details</a>
                                )
                            </li>
                            <li> Please fill the list of mandatory fields for each schedule. (TECH ID, DATE, WORK ORDER,
                                PRICE)
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

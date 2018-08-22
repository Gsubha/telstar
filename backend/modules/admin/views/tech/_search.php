<?php

use common\models\Vendor;
use common\models\Location;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model common\models\UserSearch */
/* @var $form ActiveForm */
?>
<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]);
?>
<div class="col-lg-12 col-md-12">
    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-search"></i>  Search
                </h3>
                <div class="clearfix"></div>
            </div>
            <section class="content boxheight">
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <?php echo $form->field($model, 'keyword'); ?>                
                        </div>
                    </div>  
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <?php
                            $locations = ArrayHelper::map(Location::find()->where(['status' => 10])->orderBy('location')->all(), 'id', 'location');
                            ?>
                            <?= $form->field($model, 'location')->dropDownList($locations, ['class' => 'form-control', 'prompt' => '--- Select Location ---']) ?>             
                        </div>
                    </div>  
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <?php
                            $vendors = ArrayHelper::map(Vendor::find()->where(['status' => 10])->orderBy('vendor_type')->all(), 'id', 'vendor_type');
                            ?>
                            <?= $form->field($model, 'vendor')->dropDownList($vendors, ['class' => 'form-control', 'prompt' => '--- Select Vendor ---']) ?>             
                        </div>
                    </div>  
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary ']) ?> &nbsp;
                            <?= Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
                        </div>
                    </div> 

                </div>
                <p><b>*Search By Keyword ( Tech ID, Username, First Name, Last Name, Email )</b></p>
            </section>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
$script = <<< JS
$(document).ready(function(){
        var height = $('.content-wrapper:visible').css('height');
        $(".customsidebar").height(height);
}); 
JS;
$this->registerJs($script, View::POS_END);
?>

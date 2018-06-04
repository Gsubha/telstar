<?php

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model LoginForm */

use common\models\LoginForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'TelStar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box">
   <div class="login-logo">
    <a href="#"><b>Telstar</b>Cable</a>
  </div>
<div class="login-box-body">
    <p class="login-box-msg login_labelname">Tech Sign In</p>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
    <div class="form-group has-feedback">
                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        </div>
 <div class="form-group has-feedback">
                <?= $form->field($model, 'password')->passwordInput() ?>
      </div>
 <div class="row">
      <div class="col-xs-8">
          <div class="checkbox icheck">
                <?php $form->field($model, 'rememberMe')->checkbox() ?>
</div> </div>
                <div class="col-xs-4">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
 </div>
            <?php ActiveForm::end(); ?>
    </div>
</div>

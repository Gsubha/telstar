<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'TelStar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box">
    <h1><?= Html::encode($this->title) ?></h1>
<div class="login-box-body">
    <p>Login Details:</p>

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
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
</div> </div>
                <div class="col-xs-4">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
 </div>
            <?php ActiveForm::end(); ?>
    </div>
</div>

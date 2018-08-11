<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Deductions */

$this->title = 'Deductions Info';
$this->params['breadcrumbs'][] = ['label' => 'Deductions', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update Deductions Info';
?>
<div class="portal-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
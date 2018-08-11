<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Techdeductions */

$this->title = 'Edit Tech Deduction';
$this->params['breadcrumbs'][] = ['label' => 'Tech Deductions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
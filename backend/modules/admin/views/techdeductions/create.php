<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Techdeductions */

$this->title = 'Create Tech Deduction';
$this->params['breadcrumbs'][] = ['label' => 'Tech Deductions', 'url' => ['tech/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <?=
    $this->render('_form', [
        'model' => $model
    ])
    ?>
</div>

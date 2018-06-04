<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Portal */

$this->title = 'Update';
$this->params['breadcrumbs'][] = ['label' => 'Portal', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="portal-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vendor */

$this->title = 'Update ';
$this->params['breadcrumbs'][] = ['label' => 'Vendor', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

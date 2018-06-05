<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Location */

$this->title = 'Update ' ;
$this->params['breadcrumbs'][] = ['label' => 'Location', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="location-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

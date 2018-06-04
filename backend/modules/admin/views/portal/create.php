<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Portal */

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Portal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

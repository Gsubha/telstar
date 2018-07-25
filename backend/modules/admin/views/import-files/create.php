<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ImportFiles */

$this->title = 'Create Import Files';
$this->params['breadcrumbs'][] = ['label' => 'Import Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="import-files-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

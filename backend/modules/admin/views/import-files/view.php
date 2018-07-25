<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ImportFiles */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Import Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="import-files-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'cat',
            'type',
            'name',
            'path',
            'created_at',
            'created_by',
            'deleted_at',
        ],
    ]) ?>

</div>

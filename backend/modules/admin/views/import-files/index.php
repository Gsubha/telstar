<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ImportFilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Uploaded Files';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content import-files-index">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            //'id',
                            ['attribute'=>'cat','label'=>'Uploaded From'],
                            'type',
                            'name',
                            //'path',
                            ['attribute'=>'created_at','label' => 'Date', 'format'=>['date', 'php:Y/m/d H:i A']],
                            //'created_by',
                            //'deleted_at',
                            ['class' => 'yii\grid\ActionColumn',
                                'header'=>"Action",
                                'template' => '{download}',
                                'buttons' => [
                                    'download' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-download"></i>', ['import-files/download?url=' . $model->path."/".$model->name], ['class' => 'bmodelButton', 'title' => 'Download']
                                        );
                                    },
                                ],
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div></div>
</section>
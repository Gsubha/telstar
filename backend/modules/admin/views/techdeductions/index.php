<?php

use common\models\User;
use common\models\TechSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TechdeductionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tech Deductions List';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                <!--<h1><?php Html::encode($this->title) ?></h1>-->

                    <div class="pull-right">
                        <?= Html::a('Add Tech Deduction', ['create'], ['class' => 'btn btn-success']) ?>
                    </div></div>
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'user.techid',
                            [
                                'label' => 'Category',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return \common\models\TechDeductions::$categories[$model->category];
                                },
                            ],
                            'deduction_info',
                            [
                                'label' => 'Deduction date',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return date('m/d/Y', strtotime($model->deduction_date));
                                },
                            ],
                            'qty',
                            'total',
                            'updated_at',
                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{update}&nbsp;&nbsp;{delete}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-fw fa-edit"></i>', ['techdeductions/update?id=' . $model->id], ['class' => 'bmodelButton', 'title' => 'Update']);
                                    },
                                    'delete' => function($url, $model) {
                                        return Html::a('<i class="fa fa-fw fa-trash"></i>', ['techdeductions/delete', 'id' => $model->id], [
                                            'class' => '',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this tech deduction?',
                                                'method' => 'post',
                                            ],
                                        ]);
//                            }
                                    },
                                ],
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
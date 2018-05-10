<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BillingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Billings';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <div class="pull-right">
        <?= Html::a('Create Billing', ['create'], ['class' => 'btn btn-success']) ?>
    </div></div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'techid',
            'type',
            'wo_complete_date',
            'work_order',
            //'techid',
            //'work_code',
            //'total',
            //'date',
            //'created_at',
            //'updated_at',
            //'created_by',
            //'updated_by',
            //'deleted_at',

          ['class' => 'yii\grid\ActionColumn',
                                'template' => '{update}&nbsp;&nbsp;{delete}',
                                'buttons' => [
//                                    'view' => function ($url, $model) {
////                                        $url = Url::toRoute('users/view?id=' . $model->id);
//                                        return Html::a('<span class="fa-eye"></span>', ['users/view?id='. $model->id], ['class' => 'modelButton', 'title' => 'View Student']
//                                        );
//                                    },
                                    'update' => function ($url, $model) {
//                                        $url = Url::toRoute('users/edit?id=' . $model->id);
                                        return Html::a('<i class="fa fa-fw fa-edit"></i>', ['billing/update?id=' . $model->id], ['class' => 'bmodelButton', 'title' => 'Update Student']
                                        );
                                    },
                                            'delete' => function($url, $model) {
//                            if ($model->dlStudentCourses->scr_paid_status == "1" && $model->dlStudentCourses->scr_completed_status == "0") {
                                        return Html::a('<i class="fa fa-fw fa-trash"></i>', ['billing/delete', 'id' => $model->id], [
                                                    'class' => '',
                                                    'data' => [
                                                        'confirm' => 'Are you sure you want to delete this tech?',
                                                        'method' => 'post',
                                                    ],
                                        ]);
//                            }
                                    },
                                        ],
                                    ],
        ],
    ]); ?>
</div>
                </div></div>
        </section>

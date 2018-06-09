<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Locations';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
      <!--<h1><?php Html::encode($this->title) ?></h1>-->

                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <div class="pull-right">
                        <?= Html::a('Create Location', ['create'], ['class' => 'btn btn-success']) ?>
                    </div></div>
                <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'location',
              [
                                'header' => 'Status',
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model['status'] == "0") {
                                        $pflag = '<span class="label label-danger">Inactive</span>';
                                    } else {
                                        $pflag = '<span class="label label-success">Active</span>';
                                    }

                                    return $pflag;
                                },
                            ],
            

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
                                        return Html::a('<i class="fa fa-fw fa-edit"></i>', ['location/update?id=' . $model->id], ['class' => 'bmodelButton', 'title' => 'Update']
                                        );
                                    },
                                            'delete' => function($url, $model) {
//                            if ($model->dlStudentCourses->scr_paid_status == "1" && $model->dlStudentCourses->scr_completed_status == "0") {
                                        return Html::a('<i class="fa fa-fw fa-trash"></i>', ['location/delete', 'id' => $model->id], [
                                                    'class' => '',
                                                    'data' => [
                                                        'confirm' => 'Are you sure you want to delete this location?',
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
            </div>
        </div></div>
</section>

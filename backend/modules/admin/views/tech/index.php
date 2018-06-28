<?php

use common\models\User;
use common\models\TechSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel UserSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Tech';
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
                        <?= Html::a('Import Tech', ['import'], ['class' => 'btn btn-info']) ?>
                        <?= Html::a('Create Tech', ['create'], ['class' => 'btn btn-success']) ?>
                    </div></div>
                <div class="box-body">
                    <?php
                    $pagesize = $dataProvider->pagination->pageSize;
                    $pageoptions = Yii::$app->myclass->pageOptions(); //Billing::$pageOptions;
                    ?>
                    <label style="color:#31708f;float: right;font-size: 1em;">Show 
                        <select name="pagesize" id="pagesize" style="padding: 2px 2px;background:white;border: 1px solid #31708f;">
                            <?php
                            foreach ($pageoptions as $key => $val) {
                                if ($pagesize == $key)
                                    echo "<option selected>" . $val . "</option>";
                                else
                                    echo "<option>" . $val . "</option>";
                            }
                            ?>
                        </select> entries per page
                    </label>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'techid',
                            'username',
                            'firstname',
                            'lastname',
                            'email',
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
//                            [
//                                'header' => 'Created By',
//                                'attribute' => 'created_by',
//                                'format' => 'raw',
//                                'value' => function ($model) {
//                                    $tech = User::findOne($model->id);
//                                    $pflag = '<span class="label label-primary">' . $tech->firstname . ' ' . $tech->lastname . '</span>';
//                                    return $pflag;
//                                },
//                            ],
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
                                        return Html::a('<i class="fa fa-fw fa-edit"></i>', ['tech/update?id=' . $model->id], ['class' => 'bmodelButton', 'title' => 'Update']
                                        );
                                    },
                                    'delete' => function($url, $model) {
//                            if ($model->dlStudentCourses->scr_paid_status == "1" && $model->dlStudentCourses->scr_completed_status == "0") {
                                        return Html::a('<i class="fa fa-fw fa-trash"></i>', ['tech/delete', 'id' => $model->id], [
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
                    ]);
                    ?>
                </div>
            </div>
        </div></div>
</section>
<?php
$js = <<< EOD
$(document).ready(function(){
    $("#pagesize").change(function(){
      k=$('form').serialize()+'&pagesize='+$(this).val();
      var pageURL = window.location.hostname + window.location.pathname;
      window.location.href=document.location.protocol +"//"+pageURL+'?'+k;
    });
    
});
EOD;
$this->registerJs($js, View::POS_END);
?>
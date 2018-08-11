<?php

use common\models\User;
use common\models\TechSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DeductionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deductions Info List';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                <!--<h1><?php Html::encode($this->title) ?></h1>-->

                    <div class="pull-right">
                        <?= Html::a('Create Deduction Info', ['create'], ['class' => 'btn btn-success']) ?>
                    </div></div>
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'name',
                            'description:ntext',
                            'price',
                            'updated_at',
                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{update}&nbsp;&nbsp;{delete}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-fw fa-edit"></i>', ['deductions/update?id=' . $model->id], ['class' => 'bmodelButton', 'title' => 'Update']);
                                    },
                                    'delete' => function($url, $model) {
                                        return Html::a('<i class="fa fa-fw fa-trash"></i>', ['deductions/delete', 'id' => $model->id], [
                                            'class' => '',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this deduction info?',
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

<?php

use common\models\Billing;
use common\models\BillingSearch;
use common\models\TechOfficial;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel BillingSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Billing Details';
$this->params['breadcrumbs'][] = $this->title;


//Current week start and end date
$staticstart = date('Y-m-d', strtotime('last Sunday'));
$staticfinish = date('Y-m-d', strtotime('next Saturday'));

$startdate=  ($searchModel->started_at) ? Billing::checkDate($searchModel->started_at) : $staticstart;
$enddate=($searchModel->ended_at) ?  Billing::checkDate($searchModel->ended_at) :  $staticfinish  ;

//Get total amount from db
//$total = 0;
//$sumofamount = Billing::find()
//        ->where(['deleted_at' => 0])
//        ->andWhere(["user_id" => Yii::$app->user->id])
//        ->andWhere('DATE_FORMAT(wo_complete_date ,"%Y-%m-%d") >= "' .$startdate. '" AND DATE_FORMAT(wo_complete_date,"%Y-%m-%d") <= "' .$enddate . '"')
//        ->sum('total');
//if ($sumofamount)
//    $total = Yii::$app->formatter->asCurrency($sumofamount, 'USD');

//Get total amount of this page 
$cost = 0;
$tech_total_amount = 0;
if (!empty($dataProvider->getModels())) {
    foreach ($dataProvider->getModels() as $key => $val) {
        $cost += $val->total;
        $tech_offcl = TechOfficial::find()->where(['user_id' => $val->user_id])->one();
        if ($tech_offcl) {
            $techval = TechOfficial::getratecode($tech_offcl->rate_code_type, $tech_offcl->rate_code_val);
             $total_amount = ($val->total) * ($techval / 100);
        }
         $tech_total_amount += (!empty($tech_offcl->rate_code_val)) ? number_format((float)$total_amount, 2, '.', '') : $val->total;
      
    }
    $cost = Yii::$app->formatter->asCurrency($cost, 'USD');
      $tech_total_amount = Yii::$app->formatter->asCurrency($tech_total_amount, 'USD');
}
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
                </div>
                <div class="box-body">
                    <!--<div class="row">-->
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                    <?php if ($cost) { ?>
                        <a href="javascript:void(0);" id="printdiv" class="btn m-b-xs  btn-success pull-right"> <i class="fa fa-print"></i>  Print</a>    
                        <div class="col-lg-12 col-md-12">&nbsp;</div>
                    <?php } ?>
                    <!--</div>-->
                    <div id="Getprintval"> 
                        <div class="col-lg-12 col-md-12">
                            <div class="row">
                                  <?php 
                    $s1=Billing::dateFormat($searchModel->started_at);
                    $e1=Billing::dateFormat($searchModel->ended_at);
                      $s2=Billing::dateFormat($staticstart);
                    $e2=Billing::dateFormat($staticfinish);
                    ?>
                                <?=
                                GridView::widget([
                                    'layout' => "<div class='panel panel-info'>"
                                    . "<div class='panel-heading'>"
                                    . "<div class='pull-right'>{summary}</div>"
                                    . "<h3 class='panel-title'>Billing Report</h3></div>"
                                    . "<div class='panel-body'>"
                                    . (($searchModel->started_at) ? "<h3>Payment Received From {$s1} until {$e1} </h3>" : "<h3>Current Week Listing From {$s2} until {$e2} </h3>")
//                                            . " <h4>Total due amount: <strong>{$total}</strong></h4>"
                                            . "  {items}{pager}</div></div>",
                                    'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
                                    'showFooter' => true,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'attribute' => 'type',
                                            'format' => 'raw',
                                            'value' => function ($model) {
                                                if ($model->type) {
                                                    if ($model->type == 'billing_details') {
                                                        $sc_stat = '<span class="label label-danger">Billing Details</span>';
                                                    } elseif ($model->type == 'all_digital_details') {
                                                        $sc_stat = '<span class="label label-success">All Digital Details</span>';
                                                    } else {
                                                        $sc_stat = '<span class="label label-info">Access Point Details</span>';
                                                    }
                                                    return $sc_stat;
                                                }
                                            },
                                        ],
                                        [
                                            'attribute' => 'wo_complete_date',
                                            'format' => ['date', 'php:m/d/Y'],
                                        ],
                                        'work_code',
                                        [
                                            'attribute' => 'work_order',
                                            'format' => 'raw',
                                            'footer' => "<strong>Total Due Amount:</strong>",
                                        ],
                                                    
                                                 [
                                                                'header' => 'Total Amount',
                                                                'attribute' => 'tech_amount',
                                                                'format' => 'raw',
                                                                'value' => function ($model) {

                                                                    $tech_offcl = TechOfficial::find()->where(['user_id' => $model->user_id])->one();
                                                                    if ($tech_offcl) {
                                                                        $val = TechOfficial::getratecode($tech_offcl->rate_code_type, $tech_offcl->rate_code_val);
                                                                        $total_amount = ($model->total) * ($val / 100);
                                                                        return '$' . number_format((float) $total_amount, 2, '.', '');
                                                                    } else {
                                                                        return '$' . $model->total;
                                                                    }
                                                                },
                                                                         'footer' => "<strong>" . $tech_total_amount . "</strong>",
                                                                    ],     
                                                    
                                                    
//                                        [
//                                            'attribute' => 'total',
//                                            'format' => 'raw',
//                                            'value' => function ($model) {
//                                                $sc_stat = '$' . $model->total;
//                                                return $sc_stat;
//                                            },
//                                            'footer' => "<strong>" . $cost . "</strong>",
//                                        ],
//                        ['class' => 'yii\grid\ActionColumn',
//                            'template' => '{delete}',
//                            'buttons' => [
////                                    'view' => function ($url, $model) {
//////                                        $url = Url::toRoute('users/view?id=' . $model->id);
////                                        return Html::a('<span class="fa-eye"></span>', ['users/view?id='. $model->id], ['class' => 'modelButton', 'title' => 'View Student']
////                                        );
////                                    },
////                                    'update' => function ($url, $model) {
//////                                        $url = Url::toRoute('users/edit?id=' . $model->id);
////                                        return Html::a('<i class="fa fa-fw fa-edit"></i>', ['billing/update?id=' . $model->id], ['class' => 'bmodelButton', 'title' => 'Update Student']
////                                        );
////                                    },
//                                'delete' => function($url, $model) {
////                            if ($model->dlStudentCourses->scr_paid_status == "1" && $model->dlStudentCourses->scr_completed_status == "0") {
//                                    return Html::a('<i class="fa fa-fw fa-trash"></i>', ['billing/delete', 'id' => $model->id], [
//                                                'class' => '',
//                                                'data' => [
//                                                    'confirm' => 'Are you sure you want to delete this data?',
//                                                    'method' => 'post',
//                                                ],
//                                    ]);
////                            }
//                                },
//                                    ],
//                                ],
                                    ],
                                ]);
                                ?>
                            </div> </div> </div>

                </div>
            </div>
        </div>
    </div>
</section>
<?php
$js = <<< EOD
$(document).ready(function(){
        
    $("#printdiv").click(function() {   
        var innerContents = document.getElementById("Getprintval").innerHTML;
        var popupWinindow = window.open('', '_blank', 'width=700,height=700,scrollbars=yes,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
        popupWinindow.document.open();
        popupWinindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="/webpanel/themes/admin/css/print.css" /></head><body onload="window.print()">' + innerContents + '</html>');    popupWinindow.document.close();  
    });      
    
});
EOD;
$this->registerJs($js, View::POS_END);
?>

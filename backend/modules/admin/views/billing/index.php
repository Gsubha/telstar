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

$this->title = 'Billings';
$this->params['breadcrumbs'][] = $this->title;


//Current week start and end date
$staticstart = date('Y-m-d', strtotime('last Sunday'));
$staticfinish = date('Y-m-d', strtotime('next Saturday'));

$startdate = ($searchModel->started_at) ? Billing::checkDate($searchModel->started_at) : $staticstart;
$enddate = ($searchModel->ended_at) ? Billing::checkDate($searchModel->ended_at) : date("Y-m-d");

//$startdate=  ($searchModel->started_at) ? date('Y-m-d',strtotime( $searchModel->started_at)) :  $staticstart;
//$enddate=($searchModel->started_at) ? date('Y-m-d',strtotime( $searchModel->ended_at)) :  $staticfinish ;
//
$key=$searchModel->keyword;
//Get total amount from db
$total = 0;
$sumofamount = Billing::find()
        ->where(['deleted_at' => 0])
        ->andWhere('DATE_FORMAT(wo_complete_date ,"%Y-%m-%d") >= "' . $startdate . '" AND DATE_FORMAT(wo_complete_date,"%Y-%m-%d") <= "' . $enddate . '"');
if($searchModel->keyword){
    $sumofamount= $sumofamount
       ->andFilterWhere([
            'or',
            ['like', 'work_order', $searchModel->keyword],
            ['like', 'techid', $searchModel->keyword],
            ['like', 'work_code', $searchModel->keyword],
        ]);
       
}
if($searchModel->type){
       $sumofamount= $sumofamount
            ->andWhere( ['type' => $searchModel->type]);  
}
$records = $sumofamount->all();
 //echo $sumofamount->createCommand()->getRawSql();  
$sumofamount= $sumofamount->sum('total');
$total = Yii::$app->formatter->asCurrency($sumofamount, 'USD');


//Get total amount of all pages
$sumoftech_total_amount = 0;
if (!empty($records)) {
    foreach ($records as $key => $val) {
        $tech_offcl = TechOfficial::find()->where(['user_id' => $val->user_id])->one();
        if ($tech_offcl) {
            $techval = TechOfficial::getratecode($tech_offcl->rate_code_type, $tech_offcl->rate_code_val);
            $total_amount = ($val->total) * ($techval / 100);
        }
        $sumoftech_total_amount += (!empty($tech_offcl->rate_code_val)) ? number_format((float) $total_amount, 2, '.', '') : $val->total;
    }
    $sumoftech_total_amount = Yii::$app->formatter->asCurrency($sumoftech_total_amount, 'USD');
}

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


                        <div class="pull-right">
                            <?= Html::a('Import Billing', ['create'], ['class' => 'btn btn-success']) ?>
                        </div></div>
                    <div class="box-body">
                        <!--<div class="row">-->
                        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                        <!--</div>-->
                        <?php if ($sumofamount != 0) { ?>
                            <a href="javascript:void(0);" id="printdiv" class="btn m-b-xs  btn-success pull-right"> <i class="fa fa-print"></i>  Print</a>    
                            <div class="col-lg-12 col-md-12">&nbsp;</div>
                        <?php } ?>
                        <!--</div>-->
                            <?php
                            $pagesize=$dataProvider->pagination->pageSize;
                            $pageoptions=Yii::$app->myclass->pageOptions();//Billing::$pageOptions;
                            ?>
                                <label style="color:#31708f;float: right;font-size: 1em;">Show 
                                    <select name="pagesize" id="pagesize" style="padding: 2px 2px;background:white;border: 1px solid #31708f;">
                                <?php
                                foreach($pageoptions as $key=>$val)
                                {
                                    if($pagesize==$key)
                                        echo "<option selected>".$val."</option>";
                                    else
                                        echo "<option>".$val."</option>";
                                }
                                ?>
                            </select> entries per page
                                </label>
                        <div id="Getprintval"> 
                            <div class="col-lg-12 col-md-12">
                                <div class="row">
                                    <?php
                                    $s1 = Billing::dateFormat($startdate);
                                    $e1 = Billing::dateFormat($enddate);
                                    $s2 = Billing::dateFormat($staticstart);
                                    $e2 = Billing::dateFormat($staticfinish);
                                    ?>
                                    <div class="table-responsive">
                                    <?=
                                    GridView::widget([
                                        'layout' => "<div class='panel panel-info'>"
                                        . "<div class='panel-heading'>"
                                        . "<div class='pull-right'>{summary}</div>"
                                        . "<h3 class='panel-title'>Billing Report</h3></div>"
                                        . "<div class='panel-body'>"
                                        . (($searchModel->started_at) ? "<h3>Payment Received From {$s1} until {$e1} </h3>" : "<h3>Current Week Listing From {$s2} until {$e2} </h3>")
                                        . " <h4>Total Price: <strong>{$total}</strong></h4>"
                                        . " <h4>Total Due Amount: <strong>{$sumoftech_total_amount}</strong></h4>"
                                        . "  {items}{pager}</div></div>",
                                        'dataProvider' => $dataProvider,
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
                                            'techid',
                                            [
                                                'attribute' => 'wo_complete_date',
                                                'format' => ['date', 'php:m/d/Y'],
                                            ],
                                            'work_code',
                                            [
                                                'attribute' => 'work_order',
                                                'format' => 'raw',
                                                'footer' => "<strong>Total Price:</strong>",
                                            ],
                                            [
                                                'header' => 'Price',
                                                'attribute' => 'total',
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    $sc_stat = '$' . $model->total;
                                                    return $sc_stat;
                                                },
                                                'footer' => "<strong>" . $cost . "</strong>",
                                            ],
                                            [
                                                'header' => 'Rate Code Type',
                                                'attribute' => 'type',
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    $tech_offcl = TechOfficial::find()->where(['user_id' => $model->user_id])->one();
                                                    if ($tech_offcl) {
                                                        return $tech_offcl->rate_code_type . ' ' . $tech_offcl->rate_code_val;
                                                    } else {
                                                        return 'not set';
                                                    }
                                                },
                                                    ],
                                                    [
                                                        'header' => 'Rate Code Percentage',
                                                        'attribute' => 'addcode',
                                                        'format' => 'raw',
                                                        'value' => function ($model) {
                                                            $tech_offcl = TechOfficial::find()->where(['user_id' => $model->user_id])->one();
                                                            if ($tech_offcl) {
                                                                return TechOfficial::getratecode($tech_offcl->rate_code_type, $tech_offcl->rate_code_val) . "%";
                                                            } else {
                                                                return '-';
                                                            }
                                                        },
                                                                   'footer' => "<strong>Total Due Amount:</strong>",
                                                            ],
                                                            [
                                                                'header' => 'Total',
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
                                                                    ['class' => 'yii\grid\ActionColumn',
                                                                        'template' => '{delete}',
                                                                        'buttons' => [
//                                    'view' => function ($url, $model) {
////                                        $url = Url::toRoute('users/view?id=' . $model->id);
//                                        return Html::a('<span class="fa-eye"></span>', ['users/view?id='. $model->id], ['class' => 'modelButton', 'title' => 'View Student']
//                                        );
//                                    },
//                                    'update' => function ($url, $model) {
////                                        $url = Url::toRoute('users/edit?id=' . $model->id);
//                                        return Html::a('<i class="fa fa-fw fa-edit"></i>', ['billing/update?id=' . $model->id], ['class' => 'bmodelButton', 'title' => 'Update Student']
//                                        );
//                                    },
                                                                            'delete' => function($url, $model) {
//                            if ($model->dlStudentCourses->scr_paid_status == "1" && $model->dlStudentCourses->scr_completed_status == "0") {
                                                                                return Html::a('<i class="fa fa-fw fa-trash"></i>', ['billing/delete', 'id' => $model->id], [
                                                                                            'class' => '',
                                                                                            'data' => [
                                                                                                'confirm' => 'Are you sure you want to delete this data?',
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
                                                                </div> </div> </div>

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

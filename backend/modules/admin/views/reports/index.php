<?php

use common\models\TechDeductions;
use common\models\TechSearch;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel common\models\TechdeductionsSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Tech Deductions Report';
$this->params['breadcrumbs'][] = $this->title;

//Current week start and end date
$staticstart = date('m/d/Y', strtotime('last Sunday'));
$staticfinish = date('m/d/Y', strtotime('next Saturday'));

$startdate = ($searchModel->startdate) ? date('m/d/Y', strtotime($searchModel->startdate)) : $staticstart;
$enddate = ($searchModel->enddate) ? date('m/d/Y', strtotime($searchModel->enddate)) : date("m/d/Y");

/* Start  */
$query = TechDeductions::find()->select(['tech_deductions.*', 'instalment_deductions.*'])->joinWith(['user', 'instalmentDeductions']);
if ($searchModel->techid != '') {
    $query->andWhere(['tech_deductions.user_id' => $searchModel->techid]);
}
if ($searchModel->startdate != "") {
    if ($searchModel->enddate == "") {
        $searchModel->enddate = date('Y-m-d');
    }
    $query->andWhere("(DATE(`tech_deductions`.`deduction_date`) BETWEEN '" . date("Y-m-d", strtotime($searchModel->startdate)) . "' AND '" . date("Y-m-d", strtotime($searchModel->enddate)) . "')
           OR (DATE(`instalment_deductions`.`inst_start_date`) BETWEEN '" . date("Y-m-d", strtotime($searchModel->startdate)) . "' AND '" . date("Y-m-d", strtotime($searchModel->enddate)) . "') "
            . "OR (DATE(`instalment_deductions`.`inst_end_date`) BETWEEN '" . date("Y-m-d", strtotime($searchModel->startdate)) . "' AND '" . date("Y-m-d", strtotime($searchModel->enddate)) . "')
          ");

    /* $query->andWhere("`tech_deductions`.`deduction_date` IS NOT NULL");
      $query->andWhere("(DATE(`tech_deductions`.`deduction_date`) BETWEEN '" . date("Y-m-d", strtotime($searchModel->startdate)) . "' AND '" . date("Y-m-d", strtotime($searchModel->enddate)) . "')
      OR (DATE(`tech_deductions`.`startdate`) BETWEEN '" . date("Y-m-d", strtotime($searchModel->startdate)) . "' AND '" . date("Y-m-d", strtotime($searchModel->enddate)) . "') "
      . "OR (DATE(`tech_deductions`.`enddate`) BETWEEN '" . date("Y-m-d", strtotime($searchModel->startdate)) . "' AND '" . date("Y-m-d", strtotime($searchModel->enddate)) . "')
      "); */
} else {
    $startdate2 = date('Y-m-d', strtotime('last Sunday'));
    $enddate2 = date('Y-m-d', strtotime('next Saturday'));
    $query->andWhere("(DATE(`tech_deductions`.`deduction_date`) BETWEEN '" . $startdate2 . "' AND '" . $enddate2 . "')
           OR (DATE(`instalment_deductions`.`inst_start_date`) BETWEEN '" . $startdate2 . "' AND '" . $enddate2 . "') "
            . "OR (DATE(`instalment_deductions`.`inst_end_date`) BETWEEN '" . $startdate2 . "' AND '" . $enddate2 . "')
          ");
//    $query->andWhere("`tech_deductions`.`deduction_date` IS NOT NULL");
//    $query->andWhere("(DATE(`tech_deductions`.`deduction_date`) BETWEEN '" . $startdate2 . "' AND '" . $enddate2 . "')
//           OR (DATE(`tech_deductions`.`startdate`) BETWEEN '" . $startdate2 . "' AND '" . $enddate2 . "') "
//            . "OR (DATE(`tech_deductions`.`enddate`) BETWEEN '" . $startdate2 . "' AND '" . $enddate2 . "')
//          ");
}
$records = $query->all();
$Record_count = $query->count();
//Overall Total Section - Start
$Overall_Total_amount = 0;
if (!empty($records)) {
    foreach ($records as $rk => $rv) {
        $main_cat_val = $rv->category;
        switch ($main_cat_val) {
            case "onetime":
                if ($rv->total) {
                    $Overall_Total_amount += $rv->total;
                }
                break;

            case "ongoing":
                if ($rv->total) {
                    $Overall_Total_amount += $rv->total;
                }
                break;
                
            case "installment":
                if ($rv->inst_paid_amt) {
                    $Overall_Total_amount += $rv->inst_paid_amt;
                }
                break;
        }
//        if ($rv->total) {
//            $Overall_Total_amount += $rv->total;
//        }
    }
}

$Overall_Total_amount = Yii::$app->formatter->asCurrency($Overall_Total_amount, 'USD');
// Overall Total Section - end
//Footer Total Section - start
$Total_amount = 0;
if (!empty($dataProvider->getModels())) {
    foreach ($dataProvider->getModels() as $key => $val) {
        if ($val->total) {
            $Total_amount += $val->total;
        }
    }
    $Total_amount = number_format((float) $Total_amount, 2, '.', '');
    $Total_amount = Yii::$app->formatter->asCurrency($Total_amount, 'USD');
}

//Footer Total Section - end


/* End */
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                    <div class="pull-right">

                    </div></div>
                <div class="box-body">
                    <?php
                    $pagesize = $dataProvider->pagination->pageSize;
                    $pageoptions = Yii::$app->myclass->pageOptions();
                    ?>
                    <div>
                        <a href="javascript:void(0);" id="printdiv" class="btn m-b-xs  btn-success"> <i class="fa fa-print"></i>  Print</a>  
                        <label style="color:#31708f;float: right;font-size: 1em;margin-top:12px;">Show 
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
                    </div>
                    <div id="Getprintval"> 
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'layout' => "<div class='panel panel-info'>"
                            . "<div class='panel-heading'>"
                            . "<div class='pull-right'>{summary}</div>"
                            . "<h3 class='panel-title'>Tech Deduction Report</h3></div>"
                            . "<div class='panel-body'>"
                            . (($searchModel->startdate) ? "<h3>Deduction Report From $startdate until $enddate </h3>" : "<h3>Current Week Report From {$staticstart} until {$staticfinish} </h3>")
                            . " <h4>Total Amount: <strong class='text-maroon'>{$Overall_Total_amount}</strong></h4>"
                            . "  {items}{pager}</div></div>",
                            'dataProvider' => $dataProvider,
                            'showFooter' => true,
//                        'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'user.techid',
                                [
                                    'label' => 'Category',
                                    'attribute' => 'category',
                                    'format' => 'raw',
                                    'value' => function ($data) {
                                        return TechDeductions::$categories[$data->category];
                                    },
                                ],
                                'deduction_info',
                                [
                                    'label' => 'Deduction date',
                                    'attribute' => 'deduction_date',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->deduction_date)
                                            return date('m/d/Y', strtotime($model->deduction_date));
                                        else
                                            return "-";
                                    },
                                ],
                                [
                                    'label' => 'Work Order',
                                    'attribute' => 'work_order',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->work_order)
                                            return $model->work_order;
                                        else
                                            return "-";
                                    },
                                ],
                                [
                                    'label' => 'Amount',
                                    'format' => 'raw',
                                    'attribute' => 'total',
                                    'value' => function($model) {
                                        $tprice = number_format((float) $model->total, 2, '.', '');
                                        if($model->category!='installment'){
                                        return "<strong class='text-maroon'>".Yii::$app->formatter->asCurrency($tprice, 'USD')."</strong>";}
                                        else{
                                        return Yii::$app->formatter->asCurrency($tprice, 'USD');}
                                    },
                                //'footer' => "<strong>" . $Total_amount . "</strong>",
                                ],
                                [
                                    'label' => 'Comments',
                                    'attribute' => 'description',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->description)
                                            return $model->description;
                                        else
                                            return "-";
                                    },
                                ],
                                [
                                    'label' => 'Start date',
                                    'attribute' => 'inst_start_date',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->inst_start_date)
                                            return date('m/d/Y', strtotime($model->inst_start_date));
                                        else
                                            return "-";
                                    },
                                ],
                                [
                                    'label' => 'End date',
                                    'attribute' => 'inst_end_date',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->inst_end_date)
                                            return date('m/d/Y', strtotime($model->inst_end_date));
                                        else
                                            return "-";
                                    },
                                ],
                                [
                                    'label' => 'Paid to Date',
                                    'attribute' => 'inst_paid_amt',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->inst_paid_amt){
                                            $inst_paid_amt = number_format((float) $model->inst_paid_amt, 2, '.', '');
                                            return "<strong class='text-maroon'>".Yii::$app->formatter->asCurrency($inst_paid_amt, 'USD')."</strong>";
                                        }
                                        else
                                            return "-";
                                    },
                                //'footer' => "<strong>Total</strong>",
                                ],
                                [
                                    'label' => 'Remainder',
                                    'attribute' => 'remain_amt',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->remain_amt)
                                            return $model->remain_amt;
                                        else
                                            return "-";
                                    },
                                //'footer' => "<strong>Total</strong>",
                                ],
                                [
                                    'label' => 'Paid Amount',
                                    'attribute' => 'total_paid_amt',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->total_paid_amt)
                                            return $model->total_paid_amt;
                                        else
                                            return "-";
                                    },
                                //'footer' => "<strong>Total</strong>",
                                ],
                            //'total',
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        var innerstyle='<style>.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td{padding: 4px;font-size: 12px;line-height: initial;}h3, .h3, h4, .h4 {font-size: 16px;margin-top:2px;margin-bottom:5px}.panel-body {    padding: 5px 15px;}</style>';
        popupWinindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="/webpanel/themes/admin/css/print.css" />' + innerstyle + '</head><body onload="window.print()">' + innerContents + '</html>');    popupWinindow.document.close();  
    });      
    
});
EOD;
$this->registerJs($js, View::POS_END);
?>
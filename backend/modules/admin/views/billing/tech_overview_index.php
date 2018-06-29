<?php

use common\models\Billing;
use common\models\BillingSearch;
use common\models\TechOfficial;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
//use yii\web\User;
use yii\web\View;

/* @var $this View */
/* @var $searchModel BillingSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Tech Overview';
$this->params['breadcrumbs'][] = $this->title;


//Current week start and end date
$staticstart = date('Y-m-d', strtotime('last Sunday'));
$staticfinish = date('Y-m-d', strtotime('next Saturday'));

$startdate = ($searchModel->started_at) ? Billing::checkDate($searchModel->started_at) : $staticstart;
$enddate = ($searchModel->ended_at) ? Billing::checkDate($searchModel->ended_at) : date("Y-m-d");

// Overall Total Section - start
$Overall_Total_price_amount = 0;
$Overall_Total_due_amount = 0;

$query = Billing::find()->joinWith(['user','techOfficial']);
if ($searchModel->location != '' || $searchModel->vendor != '') {
    $query->joinWith('techProfile');
}
 $query->select(['SUM(ROUND((billing.total),2)) AS price','SUM(ROUND(((billing.total)*(tech_official.rate_percent)/100),2)) AS total_dAmt']);
if ($searchModel->location) {
    $query->andWhere(['tech_profile.location_id' => $searchModel->location]);
}
if ($searchModel->vendor) {
    $query->andWhere(['tech_profile.vendor_id' => $searchModel->vendor]);
}
if ($searchModel->techid) {
    $query->andWhere(["billing.techid" => $searchModel->techid]);
}

$query->andWhere(['billing.deleted_at' => 0])->andWhere('DATE_FORMAT(billing.wo_complete_date ,"%Y-%m-%d") >= "' . $startdate . '" AND DATE_FORMAT(billing.wo_complete_date,"%Y-%m-%d") <= "' . $enddate . '"');
$query->groupBy(['billing.user_id']);
//echo "<div style='padding:10px;border:1px solid;color:green;'>".$query->createCommand()->getRawSql()."</div>";  
$records=$query->all();
if(!empty($records))
{
    foreach($records as $rk => $rv)
    {
        $Overall_Total_price_amount += $rv->price;
        if($rv->total_dAmt==0){
           $Overall_Total_due_amount += $rv->price;
        }
       else {
          $Overall_Total_due_amount += $rv->total_dAmt; 
       }
    }
}

$Overall_Total_price_amount= Yii::$app->formatter->asCurrency($Overall_Total_price_amount, 'USD');
$Overall_Total_due_amount= Yii::$app->formatter->asCurrency($Overall_Total_due_amount, 'USD');
// Overall Total Section - end

// Footer Total section - start
$Total_price_amount = 0;
$Total_due_amount = 0;
if (!empty($dataProvider->getModels())) {
    foreach ($dataProvider->getModels() as $key => $val) {
        $Total_price_amount += $val->price;
        if($val->total_dAmt==0){
             $Total_due_amount += $val->price;
        }else{
        $Total_due_amount += $val->total_dAmt;
        }
    }
    $Total_price_amount = number_format((float) $Total_price_amount, 2, '.', '');
    $Total_price_amount = Yii::$app->formatter->asCurrency($Total_price_amount, 'USD');

    $Total_due_amount = number_format((float) $Total_due_amount, 2, '.', '');
    $Total_due_amount = Yii::$app->formatter->asCurrency($Total_due_amount, 'USD');
}
// Footer Total section - end
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <!--<div class="row">-->
                    <?php echo $this->render('tech_overview_search', ['model' => $searchModel]); ?>
                    <!--</div>-->
                    <a href="javascript:void(0);" id="printdiv" class="btn m-b-xs  btn-success pull-right"> <i class="fa fa-print"></i>  Print</a>    
                    <div class="col-lg-12 col-md-12">&nbsp;</div>
                    <!--</div>-->
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
                                        . "<h3 class='panel-title'>Tech Overview Report</h3></div>"
                                        . "<div class='panel-body'>"
                                        . (($searchModel->started_at) ? "<h3>Payment Received From {$s1} until {$e1} </h3>" : "<h3>Current Week Listing From {$s2} until {$e2} </h3>")
                                        . " <h4>Total Price: <strong>{$Overall_Total_price_amount}</strong></h4>"
                                        . " <h4>Total Due Amount: <strong>{$Overall_Total_due_amount}</strong></h4>"
                                        . "  {items}{pager}</div></div>",
                                        'dataProvider' => $dataProvider,
                                        'showFooter' => true,
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],
                                            'techid',
                                            [
                                                'header' => 'Total Jobs',
                                                'attribute' => 'jobs',
                                            ],
                                            [
                                                'header' => 'Total Price',
                                                'attribute' => 'price',
                                                'value' => function($model) {
                                                    $tprice = number_format((float) $model->price, 2, '.', '');
                                                    return Yii::$app->formatter->asCurrency($tprice, 'USD');
                                                },
                                                'footer' => "<strong>" . $Total_price_amount . "</strong>",
                                            ],
                                            [
                                                'header' => 'Total Amount',
                                                'attribute' => 'total_dAmt',
                                                'format' => 'raw',
                                                'value' => function($model) {
                                                    $Total_dueAmt = number_format((float) $model->total_dAmt, 2, '.', '');
                                                    if ($Total_dueAmt == 0) {
                                                        $Total_dueAmt = number_format((float) $model->price, 2, '.', '');
                                                    }
                                                    return Yii::$app->formatter->asCurrency($Total_dueAmt, 'USD');
                                                },
                                                'footer' => "<strong>" . $Total_due_amount . "</strong>",
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

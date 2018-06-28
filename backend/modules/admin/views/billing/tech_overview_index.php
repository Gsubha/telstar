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
                                                'value' => function($model){
                                                $tprice = number_format((float) $model->price, 2, '.', '');
                                                return Yii::$app->formatter->asCurrency($tprice, 'USD');
                                                }
                                            ],
                                            [
                                                'header' => 'Total Amount',
                                                'attribute' => 'total_dAmt',
                                                'format' => 'raw',
                                                 'value' => function($model){
                                                    $Total_dueAmt = number_format((float) $model->total_dAmt, 2, '.', '');
                                                    if($Total_dueAmt==0){$Total_dueAmt=number_format((float) $model->price, 2, '.', '');}
                                                    return Yii::$app->formatter->asCurrency($Total_dueAmt, 'USD');
                                                    
                                                },
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

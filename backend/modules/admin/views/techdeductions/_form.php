<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Techdeductions */
/* @var $form yii\widgets\ActiveForm */
?>
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <?php
                $form = ActiveForm::begin(['id' => 'active-form',
                            'options' => [
                                'class' => 'form-horizontal',
                                'enctype' => 'multipart/form-data',
                            ],
                            'fieldConfig' => [
                                'template' => "{label}<div class=\"col-sm-8\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                            ],
                            'enableClientValidation' => true,
                                ]
                );
                ?>
                <div class="box-body">
                    <div class="form-group" style="display: none;">
                        <div class="col-md-8">
                            <?php
                            if (isset($_GET['tech_id'])) {
                                $model->user_id = $_GET['tech_id'];
                            }
                            ?>
                            <?= $form->field($model, 'user_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\User::find()->where(['not', ['techid' => null]])->all(), 'id', 'techid'), ['class' => 'form-control', 'prompt' => '--- Select Tech ---'])->label('Tech ID*'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8">
                            <?= $form->field($model, 'category')->dropDownList(\common\models\TechDeductions::$categories, ['class' => 'form-control', 'prompt' => '--- Select Category ---'])->label('Category*'); ?>
                        </div>
                    </div>
                    <div class="form-group ongoing_divs hideshow" id='ongoing_type_div'>
                        <div class="col-md-8">
                            <?php
                            if (@$model->deduction_info) {
                                $model->deduction_info = $model->deduction_info;
                            } else {
                                $model->deduction_info = "";
                            }
                            ?>
                            <?= $form->field($model, 'deduction_info')->dropDownList(\common\models\TechDeductions::$ongoing_categories, ['class' => 'form-control', 'prompt' => '--- Select Type ---'])->label('Deduction Type*'); ?>
                        </div>
                    </div>
                    <div class="meter_lease hideshow ongoing_divs">
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'serial_num')->textInput()->label('Serial Number*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->deduction_date) {
                                    $model->issue_date = date('m/d/Y', strtotime($model->deduction_date));
                                } else {
                                    $model->issue_date = date('m/d/Y');
                                }
                                ?>
                                <?= $form->field($model, 'issue_date')->textInput(['class' => 'form-control datepicker'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Issue Date*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'total')->textInput(['maxlength' => true])->label('Amount*'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="van_lease hideshow ongoing_divs">
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->yes_or_no) {
                                    $model->van_yes = 1;
                                } else {
                                    $model->van_yes = 0;
                                }
                                echo $form->field($model, 'van_yes')->checkbox(['label' => ''])->label('Yes');
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'vin')->textInput()->label('Vin#*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->deduction_date) {
                                    $model->van_deduction_date = date('m/d/Y', strtotime($model->deduction_date));
                                } else {
                                    $model->van_deduction_date = date('m/d/Y');
                                }
                                ?>
                                <?= $form->field($model, 'van_deduction_date')->textInput(['class' => 'form-control datepicker', 'id' => 'van_deduction_date'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Date*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->total) {
                                    $model->van_amount = $model->total;
                                } else {
                                    $model->van_amount = "";
                                }
                                ?>
                                <?= $form->field($model, 'van_amount')->textInput(['maxlength' => true, 'id' => 'van_amount'])->label('Amount*'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="wcgl_lease hideshow ongoing_divs">
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->yes_or_no) {
                                    $model->wcgl_yes = 1;
                                } else {
                                    $model->wcgl_yes = 0;
                                }
                                echo $form->field($model, 'wcgl_yes')->checkbox(['label' => ''])->label('Yes');
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'percentage')->dropDownList(\common\models\TechDeductions::$wcgl_percentages, ['class' => 'form-control', 'prompt' => '--- Select Percentage ---'])->label('Percentage*'); ?>
                            </div>
                        </div>
                    </div>

                    <?php /* One time deduction form - Start */ ?>
                    <div class="onetime hideshow">
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->deduction_info) {
                                    $model->onetime_deduction_type = $model->deduction_info;
                                } else {
                                    $model->onetime_deduction_type = "";
                                }
                                ?>
                                <?= $form->field($model, 'onetime_deduction_type')->textInput(['id=>onetime_deduction_type'])->label('Deduction Type*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->deduction_date) {
                                    $model->deduction_date = date('m/d/Y', strtotime($model->deduction_date));
                                } else {
                                    $model->deduction_date = date('m/d/Y');
                                }
                                ?>
                                <?= $form->field($model, 'deduction_date')->textInput(['class' => 'form-control datepicker', 'id' => 'onetime_deduction_date'], ['maxlength' => true])->label('<i class="fa fa-calendar"></i> Deduction Date*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'work_order')->textInput()->label('Work Order*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->total) {
                                    $model->onetime_amt = $model->total;
                                } else {
                                    $model->onetime_amt = "";
                                }
                                ?>
                                <?= $form->field($model, 'onetime_amt')->textInput(['maxlength' => true, 'id' => 'onetime_amount'])->label('Amount*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'description')->textarea(['id' => 'onetime_comment'])->label('Comment*'); ?>
                            </div>
                        </div>
                    </div>
                    <?php /* One time deduction form - End */ ?>
                    <?php /* Installment Deduction form - Start  */ ?>
                    <div class="form-group installment hideshow">
                        <div class="col-md-8">
                            <?php
                            if (@$model->deduction_info) {
                                $model->installment_type = $model->deduction_info;
                            } else {
                                $model->installment_type = "";
                            }
                            ?>
                            <?= $form->field($model, 'installment_type')->dropDownList(\common\models\TechDeductions::$installment_categories, ['class' => 'form-control', 'prompt' => '--- Select Type ---'])->label('Installment Type*'); ?>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->total) {
                                    $model->installment_amount = $model->total;
                                } else {
                                    $model->installment_amount = "";
                                }
                                ?>
                                <?= $form->field($model, 'installment_amount')->textInput(['maxlength' => true, 'class' => 'form-control autogen', 'id' => 'installment_amount'])->label('Amount*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?= $form->field($model, 'num_installment')->textInput(['maxlength' => true, 'class' => 'form-control autogen'])->label('Number of Installments*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->description) {
                                    $model->installment_comment = $model->description;
                                } else {
                                    $model->installment_comment = "";
                                }
                                ?>
                                <?= $form->field($model, 'installment_comment')->textarea()->label('Comment*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->startdate) {
                                    $model->startdate = date('m/d/Y', strtotime($model->startdate));
                                }
                                ?>
                                <?= $form->field($model, 'startdate')->textInput(['class' => 'form-control hideweekdays autogen', 'id' => 'inst_startweek_date'])->label('<i class="fa fa-calendar"></i> Start Week Date*'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <?php
                                if (@$model->enddate) {
                                    $model->enddate = date('m/d/Y', strtotime($model->enddate));
                                }
                                ?>
                                <label class="col-sm-3 control-label" for="inst_endweek_date"><i class="fa fa-calendar"></i> End Week Date*</label>
                                <div class="col-sm-8">
                                    <label class="inst_enddate"><?php echo @$model->enddate; ?></label>
                                    <input type="hidden" value="<?php echo @$model->enddate; ?>" id="inst_enddate" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group autotable_div">
                            <div class="col-md-9">
                                <!--<label class="col-sm-3 col-md-9 control-label">&nbsp;</label>-->
                                <div class="col-sm-12 col-md-9 col-md-offset-2 autotable">
                                    <?php
                                    if (!$model->isNewRecord) {
                                        if ($model->category == 'installment') {
                                            echo "<center><code><big>Installment Deduction</big></code></center>";
                                            echo "<table class='table'>";
                                            echo "<tr><th>S.no</th><th>Start Date</th><th>End Date</th><th>Amount</th><th>Payment Status</th><th>Paid Date</th></tr>";
                                            //$StDate = date('m/d/Y', strtotime($model->startdate));
                                            $num_inst = $model->num_installment;
                                            $inst_price = "$ " . number_format((float) ($model->total / $num_inst), 2, '.', '');
                                            $payment_status_arr = ['Not Paid' => 'NP', 'Paid' => 'P'];
                                            $inst_ded_model = new common\models\InstalmentDeductions;
                                            $all_datas = $inst_ded_model->findall(['tech_deductions_id' => $model->id]);
                                            $i = 1;
                                            foreach ($all_datas as $res) {
                                                echo "<tr><td>$i</td><td>" . date('m/d/Y', strtotime($res->inst_start_date)) . "<td>" . date('m/d/Y', strtotime($res->inst_end_date)) . "</td><td>$inst_price</td>";
                                                echo "<td>";
                                                $sel = '';
                                                if ($res->paid_date) {
                                                    $paid_date = date('m/d/Y', strtotime($res->paid_date));
                                                } else {
                                                    $paid_date = NULL;
                                                }

                                                echo "<select class='form-control' name=paid[status][$i]>";
                                                foreach ($payment_status_arr as $ps_key => $ps_val) {
                                                    if ($res->paid_status == $ps_val) {
                                                        echo "<option value='$ps_val' selected >$ps_key</option>";
                                                    } else {
                                                        echo "<option value='$ps_val' >$ps_key</option>";
                                                    }
                                                }

                                                echo "</select>"
                                                . "</td>"
                                                . "<td>"
                                                . "<input type='text' value='" . $paid_date . "' class='form-control insdatepicker'  id='paid_date_$i' name=paid[date][$i]  />"
                                                . "</td>"
                                                . "</tr>";
//                                            $model->inst_start_date = $StDate;
//                                            $StDate = date('m/d/Y', strtotime($StDate . ' + 7 days'));
                                                $i++;
                                            }

                                            /* for ($i = 1; $i <= $num_inst; $i++) {
                                              echo "<tr><td>$i</td><td>$StDate<td>" . date('m/d/Y', strtotime($StDate . ' + 6 days')) . "</td><td>$inst_price</td>";
                                              echo "<td>";
                                              $sel='';


                                              echo "<select class='form-control' name=paid[status][$i]>";
                                              foreach($payment_status_arr as $ps_key => $ps_val){
                                              if($model->paid_status == $ps_val){
                                              echo  "<option value='$ps_val' selected >$ps_key</option>";
                                              }else{
                                              echo  "<option value='$ps_val' >$ps_key</option>";
                                              }
                                              }
                                              echo "</select>"
                                              . "</td>"
                                              . "<td>"
                                              . "<input type='text' class='form-control insdatepicker'  id='paid_date_$i' name=paid[date][$i]  />"
                                              . "</td>"
                                              . "</tr>";
                                              $model->inst_start_date = $StDate;
                                              $StDate = date('m/d/Y', strtotime($StDate . ' + 7 days'));
                                              } */
                                            echo "</table>";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php /* Installment Deduction form - End */ ?>

                    <div class="box-footer">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>
<?php
$callback = Yii::$app->urlManager->createUrl(['admin/techdeductions/get_ongoing_types']);
$script = <<< JS
$(document).ready(function(){
    $(".hideshow").hide();
    var category = '{$model->category}';
    showFields(category);
    $(".autogen").on("blur", function(){
    get_end_date();
    });
    
    $('.insdatepicker').datepicker({
    autoclose: true
    });
    
    $('.datepicker').datepicker({
      //dateFormat: 'yy-mm-dd' ,
      autoclose: true
    });
   
   $('.hideweekdays').datepicker({
    daysOfWeekDisabled: [1,2,3,4,5,6],
   }); 
   
    
    $("#techdeductions-category").on("change", function(){
        $(".hideshow").hide();
        var Category = $("#techdeductions-category").val();
        showFields(Category);
    });
    
    $("#techdeductions-deduction_info").on("change",function(){
        $(".hideshow").hide();
        var ongoingType=$('#techdeductions-deduction_info').val();
        showOngoingType(ongoingType);
    });
    
    
    
    function showFields(Category){ 
        switch(Category)
        {
            case "ongoing":
                var deduction_type = '{$model->deduction_info}';
                $('#techdeductions-deduction_info').prop('selectedIndex',0);
                if(deduction_type!=''){
                    $("#techdeductions-deduction_info").val(deduction_type);
                    showOngoingType(deduction_type);
                }
                $("#ongoing_type_div").show();
            break;
            
            case "onetime":
                $(".onetime").show();
            break;
                
            case "installment":
                $(".installment").show();
            break;
    
            default:
                $(".hideshow").hide();
            break;
        }
    }
    
    function showOngoingType(ongoingType)
    {
        $("#ongoing_type_div").show();
        switch(ongoingType)
        {
            case "Meter":
            $(".meter_lease").show();
            break;
    
            case "Truck":
            $(".van_lease").show();
            break;
    
            case "WC/GL":
            $(".wcgl_lease").show();
            break;
        }
    }
                
    function get_end_date() {
        var start_date = document.getElementById('inst_startweek_date').value;
        var ins_count= $("#techdeductions-num_installment").val();
        var amt = $("#installment_amount").val();
        var err_msg='';
        if(amt==''){
        err_msg='Please fill amount';
        }
        if(ins_count==''){
        err_msg=err_msg + ' <br> Please fill number of instalment value';
        }       
        
        if(err_msg=='' && (parseInt(ins_count) > 0) && (Number(amt) > 0) && start_date!='')
        {
        
        var date = new Date(start_date);
        var newdate = new Date(date);
        var days_count = (7*ins_count)-1;
        newdate.setDate(newdate.getDate() + days_count);

        var dd = ('0' + newdate.getDate()).slice(-2); 
        var mm = ('0' + (newdate.getMonth()+1)).slice(-2);
        var y = newdate.getFullYear();

        var endDate = mm + '/' + dd + '/' + y;
        $(".inst_enddate").html(endDate);
        document.getElementById('inst_enddate').value = endDate;
        generate_table(start_date,ins_count,amt);
        $(".autotable_div").show();
        $('.insdatepicker').datepicker({
        autoclose: true
        });
        }
        else{
          $(".autotable_div").hide();
          $(".autotable").html('');
          $(".inst_enddate").html('');
          document.getElementById('inst_enddate').value = '';     
        }
    }
                
    function generate_table(start_date, ins_count, amt)
    {
        var table_start = "<table class='table'>";
        var table_heading = "<th>S.no</th><th>Start Date</th><th>End Date</th><th>Amount</th><th>Payment Status</th><th>Paid Date</th>";
        var inst_amt = ((parseFloat)(amt) / (parseInt)(ins_count)).toFixed(2);
        var table_rows = '';
        var dd='';
        for (i = 1; i <= ins_count; i++)
        {
            var startDate = new Date(start_date);
            var newdate = new Date(startDate);
            var days_count = 6 ;
            newdate.setDate(newdate.getDate() + days_count);
            var dd = ('0' + newdate.getDate()).slice(-2); 
            var mm = ('0' + (newdate.getMonth()+1)).slice(-2);
            var y =  newdate.getFullYear();

            var endDate = mm + '/' + dd + '/' + y;
            table_rows += "<tr><td>"+i+"</td><td>" + start_date + "</td><td>" + endDate + "</td><td>$ " + inst_amt + "</td><td><select class='form-control' name=paid[status]["+i+"]><option value='NP'>Not Paid</option><option value='P'>Paid</option></select></td><td><input type='text' class='form-control insdatepicker'  id='paid_date_"+i+"' name=paid[date]["+i+"]/></td>";
            new_start_date = new Date(endDate);
            new_start_date.setDate(new_start_date.getDate() + 1);

            var new_dd = ('0' + new_start_date.getDate()).slice(-2);
            var new_mm = ('0' + (new_start_date.getMonth()+1)).slice(-2);
            var new_y = new_start_date.getFullYear();

            var start_date = new_mm + '/' + new_dd + '/' + new_y;
            
        }
        var table_end = "</table>";
        var table_datas = "<center><code><big>Installment Deduction</big></code></center>"+table_start + table_heading + table_rows + table_end;
        $(".autotable").html(table_datas);
    }
                
    
    
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>
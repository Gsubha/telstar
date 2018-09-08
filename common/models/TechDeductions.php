<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tech_deductions".
 *
 * @property int $id
 * @property int $user_id
 * @property int $deduction_id
 * @property string $qty
 * @property string $total
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $deduction_date
 *
 * @property Deductions $deduction
 * @property User $user
 */
class TechDeductions extends \yii\db\ActiveRecord {

    public $file, $techid, $van_deduction_date, $van_amount, $van_yes, $wcgl_yes, $onetime_amt, $onetime_deduction_type, $issue_date,$ongoing_type;
    public $installment_type, $installment_amount, $installment_comment;
    public $inst_start_date,$inst_end_date,$total_amt_paid,$remain_amt,$inst_no,$inst_paid_amt,$total_paid_amt;
    public static $categories = ["ongoing" => "On-going deduction", "onetime" => "One time deduction", "installment" => "Installment deduction"];
    public static $ongoing_categories = ["Meter" => "Meter Lease", "Truck" => "Van Lease", "WC/GL" => "WC/GL"];
    public static $wcgl_percentages = ["5" => "5 %", "8" => "8 %", "10" => "10 %", "12" => "12 %", "15" => "15 %"];
    public static $installment_categories = ['Retainer' => 'Retainer – 5% or 10%', 'Damages' => 'Damage Claim', 'Lodging' => 'Lodging', 'Loan' => 'Loan', 'Tools' => 'Tools', 'Lost CPE' => 'Lost CPE'];

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tech_deductions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['category'], 'required'],
            [['file'], 'required', 'on' => 'import'],
            //[['user_id', 'category', 'total','deduction_date'], 'required'],
            [['num_installment'], 'integer','min'=>1],
            [['total','installment_amount'], 'number','min'=>1],
            [['created_at', 'updated_at', 'deleted_at', 'description', 'deduction_date', 'category', 'deduction_info', 'ongoing_type', 'serial_num', 'yes_or_no', 'vin', 'percentage', 'work_order', 'van_deduction_date', 'van_amount', 'file'], 'safe'],
            // [['deduction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deductions::className(), 'targetAttribute' => ['deduction_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['file'], 'file'],
            /* Ongoing Category Validation - Start */
            [['percentage'], 'required', 'when' => function ($model) {
                    return $model->ongoing_type == "WC/GL";
                }, 'whenClient' => "function (attribute, value) {
                        return $('#techdeductions-deduction_info').val() == 'WC/GL';
                    }",
            ],
            [['serial_num', 'total', 'issue_date'], 'required', 'when' => function ($model) {
                    return $model->ongoing_type == "Meter";
                }, 'whenClient' => "function (attribute, value) {
                        return $('#techdeductions-deduction_info').val() == 'Meter';
                    }",
            ],
            [['vin', 'van_amount', 'van_deduction_date'], 'required', 'when' => function ($model) {
                    return $model->ongoing_type == "Truck";
                }, 'whenClient' => "function (attribute, value) {
                        return $('#techdeductions-deduction_info').val() == 'Truck';
                    }",
            ],
            [['deduction_info'], 'required', 'when' => function ($model) {
                    return $model->category == "ongoing";
                }, 'whenClient' => "function (attribute, value) {
                        return $('#techdeductions-category').val() == 'ongoing';
                    }",
            ],
            /* Ongoing Category Validation - End */

            /* OneTime Category Validation - Start */
            [['onetime_deduction_type', 'deduction_date', 'work_order', 'onetime_amt', 'description'], 'required', 'when' => function ($model) {
                    return $model->category == "onetime";
                }, 'whenClient' => "function (attribute, value) {
                        return $('#techdeductions-category').val() == 'onetime';
                    }",
            ],
            /* OneTime Category Validation - End */

            /* Installment Category Validation - Start */
            [['installment_type', 'installment_amount', 'num_installment', 'installment_comment', 'startdate','enddate'], 'required', 'when' => function ($model) {
                    return $model->category == "installment";
                }, 'whenClient' => "function (attribute, value) {
                        return $('#techdeductions-category').val() == 'installment';
                    }",
            ],
            /* Installment Category Validation - End */
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'category' => "Category",
            'user_id' => 'Tech ID',
            // 'deduction_id' => 'Deduction Info',
            'deduction_info' => 'Deduction Type',
            'qty' => 'Qty',
            'total' => 'Amount',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'deduction_date' => "Deduction Date",
            'startdate' => "Start Date",
            'enddate' => "End Date",
            'ongoing_type' => 'On-Going Type',
            'serial_num' => 'Serial Number',
            'vin' => 'Vin#',
            'percentage' => 'Percentage',
            'work_order' => 'Work Order',
            'description' => 'Comment',
            'onetime_deduction_type' => 'Deduction Type',
            'onetime_amt' => 'Amount',
            'van_amount' => 'Amount',
            'van_deduction_date' => 'Date',
            'installment_type' => 'Installment Type',
            'num_installment' => 'Number of Installment',
            'installment_amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeduction() {
        return $this->hasOne(Deductions::className(), ['id' => 'deduction_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function checkDate($date) {
        return date('Y-m-d', strtotime($date));
    }

    public function getTechIds() {
        $q = TechDeductions::find()->joinWith(['user'])->select(['tech_deductions.user_id', 'user.techid'])->groupBy("tech_deductions.user_id")->all();
        if ($q) {
            return $q;
        } else {
            return array();
        }
    }
    
    public function getInstalmentDeductions() {
        return $this->hasMany(InstalmentDeductions::className(), ['tech_deductions_id' => 'id']);
    }

}

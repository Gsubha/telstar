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
class TechDeductions extends \yii\db\ActiveRecord
{

    public static $categories = [ "ongoing" => "On-going deduction", "onetime" => "One time deduction", "periodic" => "Periodic deduction"];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tech_deductions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'category', 'total','deduction_date'], 'required'],
            [['qty', 'total'], 'number'],
            [['created_at', 'updated_at', 'deleted_at','description','deduction_date','category','deduction_info'], 'safe'],
           // [['deduction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deductions::className(), 'targetAttribute' => ['deduction_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => "Category",
            'user_id' => 'Tech ID',
           // 'deduction_id' => 'Deduction Info',
            'deduction_info' => 'Deduction Info',
            'qty' => 'Qty',
            'total' => 'Amount',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'deduction_date' => "Deduction Date",
            'startdate' => "Start Date",
            'enddate' => "End Date"
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeduction()
    {
        return $this->hasOne(Deductions::className(), ['id' => 'deduction_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function checkDate($date)
    {
        return date('Y-m-d', strtotime($date));
    }
}

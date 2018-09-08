<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "instalment_deductions".
 *
 * @property int $id
 * @property int $tech_deductions_id
 * @property string $inst_start_date
 * @property string $inst_end_date
 * @property string $inst_paid_amt
 *
 * @property TechDeductions $techDeductions
 */
class InstalmentDeductions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instalment_deductions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tech_deductions_id'], 'required'],
            [['tech_deductions_id'], 'integer'],
            [['inst_start_date', 'inst_end_date'], 'safe'],
            [['inst_paid_amt'], 'number'],
            [['tech_deductions_id'], 'exist', 'skipOnError' => true, 'targetClass' => TechDeductions::className(), 'targetAttribute' => ['tech_deductions_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tech_deductions_id' => 'Tech Deductions ID',
            'inst_start_date' => 'Inst Start Date',
            'inst_end_date' => 'Inst End Date',
            'inst_paid_amt' => 'Inst Paid Amt',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTechDeductions()
    {
        return $this->hasOne(TechDeductions::className(), ['id' => 'tech_deductions_id']);
    }
}

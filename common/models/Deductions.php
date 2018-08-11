<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deductions".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property int $created_at
 * @property int $updated_at
 *
 * @property TechDeductions[] $techDeductions
 */
class Deductions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deductions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['price'], 'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTechDeductions()
    {
        return $this->hasMany(TechDeductions::className(), ['deduction_id' => 'id']);
    }
}

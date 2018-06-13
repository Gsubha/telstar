<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tech_vehicle".
 *
 * @property int $id
 * @property int $user_id
 * @property string $license_plate
 * @property string $state
 * @property string $registration
 * @property string $reg_exp
 * @property string $insurance_company
 * @property string $insurance_exp
 * @property string $driver_license
 * @property string $issuing_state
 *
 * @property User $user
 */
class TechVehicle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tech_vehicle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
             [['license_plate', 'registration', 'reg_exp', 'insurance_company', 'insurance_exp', 'driver_license', 'issuing_state'], 'safe'],
            //[['license_plate', 'registration', 'reg_exp', 'insurance_company', 'insurance_exp', 'driver_license', 'issuing_state'], 'string', 'max' => 255],
            [['state'], 'string', 'max' => 100],
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
            'user_id' => 'User ID',
            'license_plate' => 'License Plate',
            'state' => 'State',
            'registration' => 'Registration',
            'reg_exp' => 'Reg Exp',
            'insurance_company' => 'Insurance Company',
            'insurance_exp' => 'Insurance Exp',
            'driver_license' => 'Driver License',
            'issuing_state' => 'Issuing State',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

<?php

namespace common\models;

use common\models\Location;
use Yii;

/**
 * This is the model class for table "tech_profile".
 *
 * @property int $id
 * @property int $user_id
 * @property int $vendor_id
 * @property int $location_id
 * @property string $work_status
 * @property string $dob
 *
 * @property User $user
 */
class TechProfile extends \yii\db\ActiveRecord
{

    public $other;

    public static $workstatusList = ["A" => "Active", "T" => "Terminated", "OL" => "On Leave"];
    public static $jobdescList = ["director" => "Director", "supervisor" => "Supervisor", "tech" => "Tech", "dispatch" => "Dispatch"];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tech_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'vendor_id', 'location_id'], 'integer'],
            [['dob', 'other', 'user_id', 'vendor_id', 'location_id'], 'safe'],
            [['work_status'], 'string', 'max' => 5],
            [['other'], 'required', 'on' => 'createvendor', 'when' => function ($tech) {
                return ($tech->vendor_id == '-1');
            }, 'whenClient' => "function (attribute, value) {
                return ($('#techprofile-vendor_id').val() =='-1');
            }"],
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
            'vendor_id' => 'Vendor ID',
            'location_id' => 'Location ID',
            'work_status' => 'Work Status',
            'dob' => 'Dob',
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

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tech_official".
 *
 * @property int $id
 * @property int $user_id
 * @property string $hire_date
 * @property string $job_desc
 * @property string $vid
 * @property string $pid
 * @property string $badge_exp_date
 * @property string $insurance_exp
 * @property string $last_background_check
 * @property string $term_date
 *
 * @property User $user
 */
class TechOfficial extends \yii\db\ActiveRecord
{
    public $inhouse;
    public $corporate;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tech_official';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['hire_date', 'badge_exp_date', 'term_date','vid', 'pid','last_background_check','inhouse','corporate','rate_code_val','rate_code_type'], 'safe'],
            [['job_desc'], 'string', 'max' => 150],
//             [['corporate'], 'required', 'on' => 'createratecode', 'when' => function ($tech_offcl) {
//                    return ($tech_offcl->rate_code_type == 'Cp');
//                }, 'whenClient' => "function (attribute, value) {
//                return ($('#techofficial-rate_code_type').val() =='Cp');
//            }"],
            [['vid', 'pid', 'insurance_exp', 'last_background_check'], 'string', 'max' => 255],
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
            'hire_date' => 'Hire Date',
            'job_desc' => 'Job Desc',
            'vid' => 'VID',
            'pid' => 'PID',
            'badge_exp_date' => 'Badge Expiry Date',
            'insurance_exp' => 'Insurance Exp',
            'last_background_check' => 'Last Background Check',
            'term_date' => 'Term Date',
        ];
    }
    
     public static function corporateList() {
                      return array("1" => "60", "2" => "70", "3" => "75", "4" => "80", "5" => "83");
       }
       
        public static function inhouseList() {
                      return array("1" => "50", "2" => "60", "3" => "65", "4" => "70");
       }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public static function calculateRateCode($other_vendor) {
        $model = Vendor::find()
                ->where(['vendor_type' => $other_vendor])
                ->one();
        if (empty($model)) {
            $vendor = new Vendor();
            $vendor->vendor_type = $other_vendor;
            $vendor->created_at = date('Y-m-d H:i:s');
            $vendor->created_by = Yii::$app->user->id;
            $vendor->save(false);
            return $vendor->id;
        } else {
            return $model->id;
        }
    }
}

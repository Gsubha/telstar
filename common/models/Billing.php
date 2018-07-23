<?php

namespace common\models;

use common\models\User;
use Yii;
//use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "billing".
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $wo_complete_date
 * @property string $work_order
 * @property string $techid
 * @property string $work_code
 * @property string $total
 * @property string $date
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_at
 */
class Billing extends ActiveRecord
{

    public $file;
    public $vendor;
    public $location,$jobs,$price,$username,$total_dAmt;
    public static $typeList = ["access_point_details" => "Access Point Details", "all_digital_details" => "All Digital Details", "billing_details" => "Billing Details"];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'billing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'file'], 'required'],
            [['user_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['type', 'wo_complete_date', 'date', 'file'], 'safe'],
            [['total'], 'number'],
            [['file'], 'file'],
//            [['file'], 'checkExtension'],
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
            'type' => 'Type',
            'wo_complete_date' => 'Wo Complete Date',
            'work_order' => 'Work Order',
            'techid' => 'Tech ID',
            'work_code' => 'Work Code',
            'total' => 'Price',
            'date' => 'Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
        ];
    }

//      public function checkExtension($attribute, $params) {
//       
//            $this->addError($attribute, "Only files with these extensions are allowed: xls, xlsx");
//        
//    }

//    public function behaviors() {
//        return [
//            'softDeleteBehavior' => [
//                'class' => SoftDeleteBehavior::className(),
//                'softDeleteAttributeValues' => [
//                    'deleted_at' => true
//                ],
//                'replaceRegularDelete' => true // mutate native `delete()` method
//            ],
//        ];
//        return [
//            TimestampBehavior::className(),
//        ];
//    }


    public static function dateFormat($date)
    {
        return date('m/d/Y', strtotime($date));
    }

    public static function checkDate($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    public static function insertTechId($techid)
    {
        $model = User::find()
            ->where(['techid' => $techid])
            ->one();

        if (empty($model)) {
            $user = new User();
            $user->username = trim($techid);
            $user->techid = trim($techid);
            $user->password_hash = Yii::$app->security->generatePasswordHash($techid);
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->created_at = date('Y-m-d H:i:s');
            $user->save();
            return $user->id;
        } else {
            return $model->id;
        }
    }

//    public static function typeList()
//    {
//        return array("access_point_details" => "Access Point Details", "all_digital_details" => "All Digital Details", "billing_details" => "Billing Details");
//    }


    public static function accessBillingCount($key)
    {
        $staticstart = date('Y-m-d', strtotime('last Sunday'));
        $staticfinish = date('Y-m-d', strtotime('next Saturday'));
        return Billing::find()->where('deleted_at =0')
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->andWhere('DATE_FORMAT(wo_complete_date ,"%Y-%m-%d") >= "' . $staticstart . '" AND DATE_FORMAT(wo_complete_date,"%Y-%m-%d") <= "' . $staticfinish . '"')
            ->andWhere(['type' => $key])
            ->sum('total');

    }

    public static function checkAccessPoint($type, $date, $techid, $work_order, $user_id, $total, $created_by)
    {
        $billing = new Billing();
        $billing->user_id = trim($user_id);
        $billing->type = trim($type);
        $billing->wo_complete_date = trim($date);
        $billing->work_order = trim($work_order);
        $billing->techid = trim($techid);
        $billing->total = trim($total);
        $billing->created_by = trim($created_by);
        $billing->save(false);
    }

    public static function checkBillingDetails($type, $date, $techid, $work_order, $user_id, $total, $created_by, $work_code)
    {
        $billing = new Billing();
        $billing->user_id = trim($user_id);
        $billing->type = trim($type);
        $billing->wo_complete_date = trim($date);
        $billing->work_order = trim($work_order);
        $billing->techid = trim($techid);
        $billing->total = trim($total);
        $billing->work_code = trim($work_code);
        $billing->created_by = trim($created_by);
        $billing->save(false);
    }
    
    public function getTechIds()
    {
        return User::find()->where(['is_admin'=>0])->orderBy(['techid'=>SORT_ASC])->all();
    }
    
    public function getVendors()
    {
        return Vendor::find()->all();
    }
    
    public function getLocations()
    {
        return Location::find()->all();
    }
    
    public function getTechProfile()
    {
        return $this->hasMany(TechProfile::className(), ['user_id' => 'user_id']);
    }
    
    public function getTech()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    public function changeDeleteStatus($billing_id=null)
    {
        if($billing_id)
        {
            $billDet=Billing::find()->where(['user_id'=>$billing_id])->one();
            if(!empty($billDet))
            {
                $billDet->deleted_at=time();
                $billDet->type=$billDet->type; // give this because type field required
                $billDet->file='test'; // give this because file field required
                $billDet->save();
            }
            else
                return false;
        }
        else
        {
           return false;
        }
    }
    
    public function getTechOfficial()
    {
        return $this->hasOne(TechOfficial::className(), ['user_id' => 'user_id']);
    }
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    public function getTechProfileInfo()
    {
        return $this->hasOne(TechProfile::className(), ['user_id' => 'user_id']);
    }
     public function getVendorInfo() {
        return $this->hasOne(Vendor::className(), ['id' => 'vendor_id'])->via('techProfileInfo');
    }
    
}

<?php

namespace common\models;

use Yii;
//use yii2tech\ar\softdelete\SoftDeleteBehavior;
use common\models\TechProfile;
use common\models\TechOfficial;
use common\models\TechVehicle;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    public $addcode;
    public $type;
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
//    public function behaviors() {
//        return [
//            'softDeleteBehavior' => [
//                'class' => SoftDeleteBehavior::className(),
//                'softDeleteAttributeValues' => [
//                    'isDeleted' => true
//                ],
//                'replaceRegularDelete' => true // mutate native `delete()` method
//            ],
//        ];
//        return [
//            TimestampBehavior::className(),
//        ];
//    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['techid','email','username'], 'unique'],
            [['username', 'techid'], 'required'],
            [['password_hash'], 'required','on' => 'create'],
            ['email', 'email'],
            [['email'], 'required','on' => 'update'],
            ['mobile', 'is10NumbersOnly'],
            [['lastname','firstname','mobile', 'addr', 'created_by', 'updated_by','created_at', 'updated_at','city', 'state', 'zip'], 'safe'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'techid' => 'Tech ID',
            'username' => 'Username',
            'password_hash' => 'Password',
            'email' => 'Email',
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'status' => 'Status',
            'addr' => 'Address',
            'mobile' => 'Mobile No',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isDeleted' => 'Is Deleted',
            'status' => "Status",
        ];
    }

    public function is10NumbersOnly($attribute) {
        if (!preg_match('/^[0-9]/', $this->$attribute)) {
            $this->addError($attribute, 'Mobile number must be a number.');
        }
    }

    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }
    
    public function getProfileUser()
    {
        return $this->hasOne(TechProfile::className(), ['user_id' => 'id']);
    }
    public function getVehicleUser()
    {
        return $this->hasOne(TechVehicle::className(), ['user_id' => 'id']);
    }
    public function getTechOfficial()
    {
        return $this->hasOne(TechOfficial::className(), ['user_id' => 'id']);
    }
    public function validateDate($date='')
    {
        if(!empty($date))
        {
            $exp=explode("/",$date);
            $month=@$exp[0];
            $day=@$exp[1];
            $year=@$exp[2];
            return @checkdate($month,$day,$year);
        }
        else{
            return false;
        }
        
    }
    
    public static function gettechname($techid=null)
    {
        $v=User::find()->where(['techid'=>$techid])->one();
        return $v->username;
        
    }
    
     public function getVendorInfo() {
        return $this->hasOne(Vendor::className(), ['id' => 'vendor_id'])->via('profileUser');
    }
    
    public function getLocationInfo() {
        return $this->hasOne(Location::className(), ['id' => 'location_id'])->via('profileUser');
    }
}

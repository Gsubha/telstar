<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vendor".
 *
 * @property int $id
 * @property string $vendor_type
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_at
 */
class Vendor extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'vendor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['vendor_type', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
            [['created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['vendor_type'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'vendor_type' => 'Vendor Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
        ];
    }

    public static function insertVendorId($other_vendor) {
        $model = Vendor::find()
                ->where(['vendor_type' => ucwords(strtolower($other_vendor))])
                ->one();
        if (empty($model)) {
            $vendor = new Vendor();
            $vendor->vendor_type = ucwords(strtolower($other_vendor));
            $vendor->created_at = date('Y-m-d H:i:s');
            $vendor->created_by = Yii::$app->user->id;
            $vendor->save(false);
            return $vendor->id;
        } else {
            return $model->id;
        }
    }

}

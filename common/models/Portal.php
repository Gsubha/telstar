<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "portal".
 *
 * @property int $id
 * @property int $admin_id
 * @property string $portal_message
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_at
 */
class Portal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'portal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['portal_message'], 'required'],
            [['admin_id', 'status', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['portal_message'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => 'Admin ID',
            'portal_message' => 'Portal Message',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
        ];
    }
}

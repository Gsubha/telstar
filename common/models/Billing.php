<?php

namespace common\models;

use Yii;

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
class Billing extends \yii\db\ActiveRecord
{
    public $upload;
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
            [['type','upload'], 'required'],
            [['user_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['wo_complete_date', 'date','upload'], 'safe'],
            [['total'], 'number'],
            [['type', 'work_order', 'work_code'], 'string', 'max' => 255],
            [['techid'], 'string', 'max' => 100],
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
            'techid' => 'Techid',
            'work_code' => 'Work Code',
            'total' => 'Total',
            'date' => 'Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
        ];
    }
}

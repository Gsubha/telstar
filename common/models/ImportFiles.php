<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "import_files".
 *
 * @property int $id
 * @property string $cat
 * @property string $type
 * @property string $name
 * @property string $path
 * @property int $created_at
 * @property int $created_by
 * @property int $deleted_at
 */
class ImportFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'import_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'deleted_at'], 'integer'],
            [['cat', 'type', 'name', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat' => 'Cat',
            'type' => 'Type',
            'name' => 'Name',
            'path' => 'Path',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'deleted_at' => 'Deleted At',
        ];
    }
}

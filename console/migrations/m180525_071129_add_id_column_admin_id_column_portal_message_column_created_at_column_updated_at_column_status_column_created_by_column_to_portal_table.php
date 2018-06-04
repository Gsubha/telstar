<?php

use yii\db\Migration;

/**
 * Handles adding id_column_admin_id_column_portal_message_column_created_at_column_updated_at_column_status_column_created_by to table `portal`.
 */
class m180525_071129_add_id_column_admin_id_column_portal_message_column_created_at_column_updated_at_column_status_column_created_by_column_to_portal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
           $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
         $this->createTable('portal', [
            'id' => $this->primaryKey(),
            'admin_id' => $this->integer()->notNull(),
            'portal_message' => $this->string(255)->notNull(),
            'created_at' => $this->smallInteger()->defaultValue(0),
            'updated_at' => $this->smallInteger()->defaultValue(0),
             'status' => $this->smallInteger()->defaultValue(0),
            'created_by' => $this->smallInteger()->defaultValue(0),
            'updated_by' => $this->smallInteger()->defaultValue(0),
            'deleted_at' => $this->smallInteger()->defaultValue(0)
        ],$tableOptions);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('portal');
    }
}

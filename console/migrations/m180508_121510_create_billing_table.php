<?php

use yii\db\Migration;

/**
 * Handles the creation of table `billing`.
 */
class m180508_121510_create_billing_table extends Migration
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
        $this->createTable('billing', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'type' => $this->string(255)->notNull(),
            'wo_complete_date' => $this->date()->Null(),
            'work_order' => $this->string(255)->Null(),
            'techid' => $this->string(100)->Null(),
            'work_code' => $this->string(255)->Null(),
            'total' => $this->decimal(10,2)->Null()->defaultValue(0.00),
             'date' => $this->date()->Null(),
            'created_at' => $this->integer()->defaultValue(0)->notNull(),
            'updated_at' => $this->integer()->defaultValue(0)->notNull(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('billing');
    }
}

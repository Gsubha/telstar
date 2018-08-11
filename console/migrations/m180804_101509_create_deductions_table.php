<?php

use yii\db\Migration;

/**
 * Handles the creation of table `deductions`.
 */
class m180804_101509_create_deductions_table extends Migration
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

        $this->createTable('deductions', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->Null(),
            'description' => $this->text()->Null(),
            'price' => $this->decimal(10,2)->Null()->defaultValue(0.00),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('deductions');
    }
}

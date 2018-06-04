<?php

use yii\db\Migration;

/**
 * Handles the creation of table `vendor`.
 */
class m180604_101339_create_vendor_table extends Migration
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
        
        $this->createTable('vendor', [
            'id' => $this->primaryKey(),
            'vendor_type' => $this->string(150)->Null(),
            'created_at' => $this->dateTime()->Null(),
            'updated_at' => $this->dateTime()->Null(),
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
        $this->dropTable('vendor');
    }
}

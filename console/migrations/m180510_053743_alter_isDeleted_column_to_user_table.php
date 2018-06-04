<?php

use yii\db\Migration;

/**
 * Class m180510_053743_alter_isDeleted_column_to_user_table
 */
class m180510_053743_alter_isDeleted_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
     $this->alterColumn('user', 'isDeleted',  $this->smallInteger()->defaultValue(0)->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->alterColumn('user', 'isDeleted',  $this->smallInteger()->defaultValue(0)->Null());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180510_053743_alter_isDeleted_column_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}

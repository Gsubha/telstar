<?php

use yii\db\Migration;

/**
 * Class m180526_095007_alter_created_at_column_to_user_table
 */
class m180526_095007_alter_created_at_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->alterColumn('user', 'created_at',  $this->dateTime()->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('user', 'created_at',  $this->dateTime()->Null());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180526_095007_alter_created_at_column_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}

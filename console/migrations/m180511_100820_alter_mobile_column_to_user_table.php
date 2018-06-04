<?php

use yii\db\Migration;

/**
 * Class m180511_100820_alter_mobile_column_to_user_table
 */
class m180511_100820_alter_mobile_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
     $this->alterColumn('user', 'mobile',  $this->string(20)->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('user', 'mobile',  $this->string(20)->Null());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180511_100820_alter_mobile_column_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}

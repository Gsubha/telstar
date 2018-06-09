<?php

use yii\db\Migration;

/**
 * Class m180608_100312_alter_email_column_to_user_table
 */
class m180608_100312_alter_email_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->alterColumn('user', 'email',  $this->string(255)->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->alterColumn('user', 'email',  $this->string(255)->notNull()->unique());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180608_100312_alter_email_column_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}

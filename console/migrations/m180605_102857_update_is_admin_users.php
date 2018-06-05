<?php

use yii\db\Migration;

/**
 * Class m180605_102857_update_is_admin_users
 */
class m180605_102857_update_is_admin_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('user', array('is_admin'=> 1), 'id=1');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180605_102857_update_is_admin_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180605_102857_update_is_admin_users cannot be reverted.\n";

        return false;
    }
    */
}

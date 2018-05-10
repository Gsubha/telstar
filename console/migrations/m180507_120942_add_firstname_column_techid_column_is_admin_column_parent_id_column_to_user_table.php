<?php

use yii\db\Migration;

/**
 * Handles adding firstname_column_techid_column_is_admin_column_parent_id to table `user`.
 */
class m180507_120942_add_firstname_column_techid_column_is_admin_column_parent_id_column_to_user_table extends Migration
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
        $this->addColumn('user', 'firstname', $this->string(100)->after('email'));
        $this->addColumn('user', 'techid', $this->string(200)->after('firstname'));
        $this->addColumn('user', 'is_admin', $this->smallInteger(4)->defaultValue(0)->after('techid'));
        $this->addColumn('user', 'parent_id', $this->smallInteger(4)->defaultValue(0)->after('is_admin'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'firstname');
        $this->dropColumn('user', 'techid');
        $this->dropColumn('user', 'is_admin');
        $this->dropColumn('user', 'parent_id');
    }
}

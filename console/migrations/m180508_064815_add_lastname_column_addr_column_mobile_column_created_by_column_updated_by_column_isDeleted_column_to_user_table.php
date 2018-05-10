<?php

use yii\db\Migration;

/**
 * Handles adding lastname_column_addr_column_mobile_column_created_by_column_updated_by_column_isDeleted to table `user`.
 */
class m180508_064815_add_lastname_column_addr_column_mobile_column_created_by_column_updated_by_column_isDeleted_column_to_user_table extends Migration
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
        $this->addColumn('user', 'lastname', $this->string(100)->after('firstname'));
        $this->addColumn('user', 'addr', $this->string(200)->after('lastname'));
        $this->addColumn('user', 'mobile', $this->integer(11)->after('addr'));
        $this->addColumn('user', 'created_by', $this->integer(11)->after('mobile'));
        $this->addColumn('user', 'updated_by', $this->integer(11)->after('created_by'));
        $this->addColumn('user', 'isDeleted', $this->smallInteger(4)->after('updated_by'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'lastname');
        $this->dropColumn('user', 'addr');
        $this->dropColumn('user', 'mobile');
        $this->dropColumn('user', 'created_by');
        $this->dropColumn('user', 'updated_by');
        $this->dropColumn('user', 'isDeleted');
    }
}

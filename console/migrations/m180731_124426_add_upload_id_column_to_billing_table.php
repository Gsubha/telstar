<?php

use yii\db\Migration;

/**
 * Handles adding upload_id to table `billing`.
 */
class m180731_124426_add_upload_id_column_to_billing_table extends Migration
{
    public function safeUp()
    {
          $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->addColumn('billing', 'upload_id', $this->integer(11)->after('deleted_at'));
    }


    public function safeDown()
    {
        $this->dropColumn('billing', 'upload_id');
    }
}

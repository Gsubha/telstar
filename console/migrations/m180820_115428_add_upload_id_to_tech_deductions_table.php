<?php

use yii\db\Migration;

/**
 * Class m180820_115428_add_upload_id_to_tech_deductions_table
 */
class m180820_115428_add_upload_id_to_tech_deductions_table extends Migration
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
        $this->addColumn('tech_deductions', 'upload_id', $this->integer(11)->defaultValue(NULL)->after('enddate'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tech_deductions', 'upload_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180820_115428_add_upload_id_to_tech_deductions_table cannot be reverted.\n";

        return false;
    }
    */
}

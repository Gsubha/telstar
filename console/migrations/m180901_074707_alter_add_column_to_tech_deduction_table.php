<?php

use yii\db\Migration;

/**
 * Class m180901_074707_alter_add_column_to_tech_deduction_table
 */
class m180901_074707_alter_add_column_to_tech_deduction_table extends Migration
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
       // $this->addColumn('tech_deductions', 'ongoing_type', $this->string(255)->defaultValue(NULL));
        $this->addColumn('tech_deductions', 'serial_num', $this->string(50)->defaultValue(NULL));
        $this->addColumn('tech_deductions', 'yes_or_no', $this->string(50)->defaultValue(NULL));
        $this->addColumn('tech_deductions', 'vin', $this->string(50)->defaultValue(NULL));
        $this->addColumn('tech_deductions', 'percentage', $this->string(50)->defaultValue(NULL));
        $this->addColumn('tech_deductions', 'work_order', $this->string(50)->defaultValue(NULL));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       // $this->dropColumn('tech_deductions', 'ongoing_type');
        $this->dropColumn('tech_deductions', 'serial_num');
        $this->dropColumn('tech_deductions', 'yes_or_no');
        $this->dropColumn('tech_deductions', 'vin');
        $this->dropColumn('tech_deductions', 'percentage');
        $this->dropColumn('tech_deductions', 'work_order');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180901_074707_alter_add_column_to_tech_deduction_table cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m180905_072622_alter_add_column_no_installment_to_tech_deduction_table
 */
class m180905_072622_alter_add_column_no_installment_to_tech_deduction_table extends Migration
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
        $this->addColumn('tech_deductions', 'num_installment', $this->integer(11)->defaultValue(NULL));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tech_deductions', 'num_installment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180905_072622_alter_add_column_no_installment_to_tech_deduction_table cannot be reverted.\n";

        return false;
    }
    */
}

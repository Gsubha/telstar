<?php

use yii\db\Migration;

/**
 * Class m180804_120804_add_description_columns_tech_deductions_table
 */
class m180804_120804_add_description_columns_tech_deductions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tech_deductions', 'description', $this->text());
        $this->addColumn('tech_deductions', 'deduction_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tech_deductions', 'description');
        $this->dropColumn('tech_deductions', 'deduction_date');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180804_120804_add_description_columns_tech_deductions_table cannot be reverted.\n";

        return false;
    }
    */
}

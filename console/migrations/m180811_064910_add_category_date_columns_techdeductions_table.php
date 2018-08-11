<?php

use yii\db\Migration;

/**
 * Class m180811_064910_add_category_date_columns_techdeductions_table
 */
class m180811_064910_add_category_date_columns_techdeductions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tech_deductions', 'category', $this->string());
        $this->addColumn('tech_deductions', 'deduction_info', $this->string());
        $this->addColumn('tech_deductions', 'startdate', $this->date());
        $this->addColumn('tech_deductions', 'enddate', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tech_deductions', 'category');
        $this->dropColumn('tech_deductions', 'deduction_info');
        $this->dropColumn('tech_deductions', 'startdate');
        $this->dropColumn('tech_deductions', 'enddate');
    }

}

<?php

use yii\db\Migration;

/**
 * Class m180811_073432_alter_qty_column_techdeductions_table
 */
class m180811_073432_alter_qty_column_techdeductions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tech_deductions', 'qty', $this->decimal(10,2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('tech_deductions', 'qty', $this->decimal(10,2)->defaultValue(0.00));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180811_073432_alter_qty_column_techdeductions_table cannot be reverted.\n";

        return false;
    }
    */
}

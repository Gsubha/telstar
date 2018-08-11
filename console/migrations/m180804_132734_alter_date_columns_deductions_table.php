<?php

use yii\db\Migration;

/**
 * Class m180804_132734_alter_date_columns_deductions_table
 */
class m180804_132734_alter_date_columns_deductions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('deductions', 'created_at',  $this->dateTime());
        $this->alterColumn('deductions', 'updated_at',  $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('deductions', 'created_at',  $this->integer());
        $this->alterColumn('deductions', 'updated_at',  $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180804_132734_alter_date_columns_deductions_table cannot be reverted.\n";

        return false;
    }
    */
}

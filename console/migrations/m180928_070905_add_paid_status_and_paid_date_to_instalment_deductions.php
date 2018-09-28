<?php

use yii\db\Migration;

/**
 * Class m180928_070905_add_paid_status_and_paid_date_to_instalment_deductions
 */
class m180928_070905_add_paid_status_and_paid_date_to_instalment_deductions extends Migration
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
        $this->addColumn('instalment_deductions', 'paid_status', $this->string(50)->defaultValue("NP"));
        $this->addColumn('instalment_deductions', 'paid_date', $this->date()->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('instalment_deductions', 'paid_status');
        $this->dropColumn('instalment_deductions', 'paid_date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180928_070905_add_paid_status_and_paid_date_to_instalment_deductions cannot be reverted.\n";

        return false;
    }
    */
}

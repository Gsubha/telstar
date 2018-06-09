<?php

use yii\db\Migration;

/**
 * Class m180607_094747_alter_status_column_to_vendor_table
 */
class m180607_094747_alter_status_column_to_vendor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->alterColumn('vendor', 'status',  $this->smallInteger(4)->defaultValue(10)->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('vendor', 'status',  $this->smallInteger(4)->Null());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180607_094747_alter_status_column_to_vendor_table cannot be reverted.\n";

        return false;
    }
    */
}

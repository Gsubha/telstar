<?php

use yii\db\Migration;

/**
 * Class m180821_051321_alter_updated_at_column_to_portal_table
 */
class m180821_051321_alter_updated_at_column_to_portal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->alterColumn('portal', 'updated_at',  $this->dateTime()->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180821_051321_alter_updated_at_column_to_portal_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180821_051321_alter_updated_at_column_to_portal_table cannot be reverted.\n";

        return false;
    }
    */
}

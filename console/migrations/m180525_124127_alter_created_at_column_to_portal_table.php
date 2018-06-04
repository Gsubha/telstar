<?php

use yii\db\Migration;

/**
 * Class m180525_124127_alter_created_at_column_to_portal_table
 */
class m180525_124127_alter_created_at_column_to_portal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
 $this->alterColumn('portal', 'created_at',  $this->dateTime()->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('portal', 'created_at',  $this->dateTime()->Null());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180525_124127_alter_created_at_column_to_portal_table cannot be reverted.\n";

        return false;
    }
    */
}

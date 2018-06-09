<?php

use yii\db\Migration;

/**
 * Class m180607_121743_alter_last_background_check_column_to_tech_official_table
 */
class m180607_121743_alter_last_background_check_column_to_tech_official_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->alterColumn('tech_official', 'last_background_check',  $this->date()->Null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('tech_official', 'last_background_check',  $this->date()->Null());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180607_121743_alter_last_background_check_column_to_tech_official_table cannot be reverted.\n";

        return false;
    }
    */
}

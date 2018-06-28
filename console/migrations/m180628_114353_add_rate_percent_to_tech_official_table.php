<?php

use yii\db\Migration;

/**
 * Class m180628_114353_add_rate_percent_to_tech_official_table
 */
class m180628_114353_add_rate_percent_to_tech_official_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn( 'tech_official','rate_percent',$this->decimal(10,2)->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('tech_official', 'rate_percent');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180628_114353_add_rate_percent_to_tech_official_table cannot be reverted.\n";

        return false;
    }
    */
}

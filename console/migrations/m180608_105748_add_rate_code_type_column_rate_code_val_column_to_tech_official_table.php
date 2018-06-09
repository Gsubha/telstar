<?php

use yii\db\Migration;

/**
 * Handles adding rate_code_type_column_rate_code_val to table `tech_official`.
 */
class m180608_105748_add_rate_code_type_column_rate_code_val_column_to_tech_official_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tech_official', 'rate_code_val', $this->integer(10));
         $this->addColumn('tech_official', 'rate_code_type', $this->string(30));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tech_official', 'rate_code_type');
         $this->dropColumn('tech_official', 'rate_code_val');
    }
}

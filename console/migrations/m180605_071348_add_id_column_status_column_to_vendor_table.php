<?php

use yii\db\Migration;

/**
 * Handles adding id_column_status to table `vendor`.
 */
class m180605_071348_add_id_column_status_column_to_vendor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('vendor', 'status',$this->smallInteger(6)->after('vendor_type'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('vendor', 'status');
    }
}

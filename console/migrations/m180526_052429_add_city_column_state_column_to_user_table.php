<?php

use yii\db\Migration;

/**
 * Handles adding city_column_state to table `user`.
 */
class m180526_052429_add_city_column_state_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $this->addColumn('user', 'city', $this->string(80)->after('addr'));
        $this->addColumn('user', 'state', $this->string(80)->after('city'));
        $this->addColumn('user', 'zip', $this->string(50)->after('state'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'city');
        $this->dropColumn('user', 'state');
        $this->dropColumn('user', 'zip');
    }
}

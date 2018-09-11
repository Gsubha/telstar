<?php

use yii\db\Migration;
/**
 * Class m180907_093438_instalment_deductions
 */
class m180907_093438_instalment_deductions extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('instalment_deductions', [
            'id' => $this->primaryKey(),
            'tech_deductions_id' => $this->integer()->notNull(),
            'inst_start_date' => $this->date()->Null(),
            'inst_end_date' => $this->date()->Null(),
            'inst_paid_amt' => $this->decimal(10, 2)->NULL(),
            'total_paid_amt' => $this->decimal(10, 2)->NULL(),
            'remain_amt' => $this->decimal(10, 2)->NULL(),
                ], $tableOptions);

        $this->createIndex(
                'idx-tech_deductions_id', 'instalment_deductions', 'tech_deductions_id'
        );

        $this->addForeignKey(
                'fk-tech_deductions_id', 'instalment_deductions', 'tech_deductions_id', 'tech_deductions', 'id', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey(
                'fk-tech_deductions_id', 'instalment_deductions'
        );

        $this->dropIndex(
                'idx-tech_deductions_id', 'instalment_deductions'
        );

        $this->dropTable('instalment_deductions');
    }
}

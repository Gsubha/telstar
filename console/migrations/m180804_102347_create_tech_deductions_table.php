<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tech_deductions`.
 */
class m180804_102347_create_tech_deductions_table extends Migration
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

        $this->createTable('tech_deductions', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->Null(),
            'deduction_id' => $this->integer()->Null(),
            'qty' => $this->decimal(10,2)->Null()->defaultValue(0.00),
            'total' => $this->decimal(10,2)->Null()->defaultValue(0.00),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'created_at' => $this->dateTime()->Null(),
            'updated_at' => $this->dateTime()->Null(),
            'deleted_at' => $this->dateTime()->Null()
        ],$tableOptions);

        $this->createIndex(
            'idx-tech_deduction-user_id',
            'tech_deductions',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-tech_deduction-user_id',
            'tech_deductions',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-tech_deduction-deduction_id',
            'tech_deductions',
            'deduction_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-tech_deduction-deduction_id',
            'tech_deductions',
            'deduction_id',
            'deductions',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-tech_deduction-user_id',
            'tech_deductions'
        );

        // drops index for column `user`
        $this->dropIndex(
            'idx-tech_deduction-user_id',
            'tech_deductions'
        );

        // drops foreign key for table `deduction`
        $this->dropForeignKey(
            'fk-tech_deduction-deduction_id',
            'tech_deductions'
        );

        // drops index for column `user`
        $this->dropIndex(
            'idx-tech_deduction-deduction_id',
            'tech_deductions'
        );

        $this->dropTable('tech_deductions');
    }
}

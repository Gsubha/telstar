<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tech_official`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m180605_104801_create_tech_official_table extends Migration
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
        $this->createTable('tech_official', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->Null(),
            'hire_date' => $this->date()->Null(),
            'job_desc' => $this->string(150)->Null(),
            'vid' => $this->string(255)->Null(),
            'pid' => $this->string(255)->Null(),
            'badge_exp_date' => $this->date()->Null(),
             'insurance_exp' => $this->string(255)->Null(),
            'last_background_check' => $this->string(255)->Null(),
            'term_date' => $this->date()->Null(),
            'created_at' => $this->dateTime()->Null(),
            'updated_at' => $this->dateTime()->Null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(0)
        ], $tableOptions);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-tech_official-user_id',
            'tech_official',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-tech_official-user_id',
            'tech_official',
            'user_id',
            'user',
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
            'fk-tech_official-user_id',
            'tech_official'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-tech_official-user_id',
            'tech_official'
        );

        $this->dropTable('tech_official');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tech_profile`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m180605_100114_create_tech_profile_table extends Migration
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
        $this->createTable('tech_profile', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->Null(),
            'vendor_id' => $this->integer()->Null(),
            'location_id' => $this->integer()->Null(),
            'work_status' => $this->string(30)->Null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'dob' => $this->date()->Null(),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(0)
        ], $tableOptions);


        // creates index for column `user_id`
        $this->createIndex(
            'idx-tech_profile-user_id',
            'tech_profile',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-tech_profile-user_id',
            'tech_profile',
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
            'fk-tech_profile-user_id',
            'tech_profile'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-tech_profile-user_id',
            'tech_profile'
        );

        $this->dropTable('tech_profile');
    }
}

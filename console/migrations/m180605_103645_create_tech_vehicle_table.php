<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tech_vehicle`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m180605_103645_create_tech_vehicle_table extends Migration
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
        $this->createTable('tech_vehicle', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->Null(),
            'license_plate' => $this->string(255)->Null(),
           'state' => $this->string(100)->Null(),
            'registration' => $this->string(255)->Null(),
            'reg_exp' => $this->string(255)->Null(),
            'insurance_company' => $this->string(255)->Null(),
            'insurance_exp' => $this->string(255)->Null(),
             'driver_license' => $this->string(255)->Null(),
             'issuing_state' => $this->string(255)->Null(),
            'created_at' => $this->dateTime()->Null(),
            'updated_at' => $this->dateTime()->Null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(10)
        ], $tableOptions);


        // creates index for column `user_id`
        $this->createIndex(
            'idx-tech_vehicle-user_id',
            'tech_vehicle',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-tech_vehicle-user_id',
            'tech_vehicle',
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
            'fk-tech_vehicle-user_id',
            'tech_vehicle'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-tech_vehicle-user_id',
            'tech_vehicle'
        );
        
        $this->dropTable('tech_vehicle');
    }
}

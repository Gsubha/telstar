<?php

use yii\db\Migration;

/**
 * Class m180723_121706_import_files
 */
class m180723_121706_import_files extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('import_files', [
            'id' => $this->primaryKey(),
            'cat' => $this->string(255)->Null(),
            'type' => $this->string(255)->Null(),
            'name' => $this->string(255)->Null(),
            'path' => $this->string(255)->Null(),
            'created_at' => $this->integer()->defaultValue(0)->notNull(),
            'created_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
                ], $tableOptions);
    }
    public function safeDown()
    {
        $this->dropTable('import_files');
    }

}

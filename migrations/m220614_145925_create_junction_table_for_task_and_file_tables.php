<?php

use yii\db\Migration;

class m220614_145925_create_junction_table_for_task_and_file_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task_file', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'file_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-task_file-task_id',
            'task_file',
            'task_id',
            'task',
            'id'
        );

        $this->addForeignKey(
            'fk-task_file-file_id',
            'task_file',
            'file_id',
            'file',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-task_file-task_id',
            'task_file'
        );

        $this->dropForeignKey(
            'fk-task_file-file_id',
            'task_file'
        );

        $this->dropTable('task_file');
    }
}

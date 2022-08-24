<?php

use yii\db\Migration;

class m220614_150200_create_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('response', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer()->notNull(),
            'comment' => $this->text(),
            'budget' => $this->integer()->notNull(),
            'is_refused' => $this->boolean()->defaultValue(0)->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);

        $this->addForeignKey(
            'fk-response-task_id',
            'response',
            'task_id',
            'task',
            'id'
        );

        $this->addForeignKey(
            'fk-response-executor_id',
            'response',
            'executor_id',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-response-task_id',
            'task'
        );

        $this->dropForeignKey(
            'fk-response-executor_id',
            'task'
        );

        $this->dropTable('response');
    }
}

<?php

use yii\db\Migration;

class m220614_143704_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue(1)->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'category_id' => $this->integer()->notNull(),
            'budget' => $this->integer(),
            'city_id' => $this->integer(),
            'coordinates' => 'POINT',
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'deadline_at' => $this->dateTime()
        ]);

        $this->addForeignKey(
            'fk-task-customer_id',
            'task',
            'customer_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-task-executor_id',
            'task',
            'executor_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-task-category_id',
            'task',
            'category_id',
            'category',
            'id'
        );

        $this->addForeignKey(
            'fk-task-city_id',
            'task',
            'city_id',
            'city',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-task-customer_id',
            'task'
        );

        $this->dropForeignKey(
            'fk-task-executor_id',
            'task'
        );

        $this->dropForeignKey(
            'fk-task-category_id',
            'task'
        );

        $this->dropForeignKey(
            'fk-task-city_id',
            'task'
        );

        $this->dropTable('task');
    }
}

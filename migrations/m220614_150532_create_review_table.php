<?php

use yii\db\Migration;

class m220614_150532_create_review_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('review', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'rate' => $this->tinyInteger(5)->notNull(),
            'comment' => $this->text()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);

        $this->addForeignKey(
            'fk-review-task_id',
            'review',
            'task_id',
            'task',
            'id'
        );

        $this->addForeignKey(
            'fk-review-author_id',
            'review',
            'author_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-review-user_id',
            'review',
            'user_id',
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
            'fk-review-task_id',
            'review'
        );

        $this->dropForeignKey(
            'fk-review-author_id',
            'review'
        );

        $this->dropForeignKey(
            'fk-review-user_id',
            'review'
        );

        $this->dropTable('review');
    }
}

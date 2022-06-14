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
            'rate' => $this->tinyInteger(5)->notNull(),
            'comment' => $this->text()
        ]);

        $this->addForeignKey(
            'fk-review-task_id',
            'review',
            'task_id',
            'task',
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

        $this->dropTable('review');
    }
}

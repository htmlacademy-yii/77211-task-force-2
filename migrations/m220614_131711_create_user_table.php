<?php

use yii\db\Migration;

class m220614_131711_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->unique()->notNull(),
            'password' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'birthdate'=> $this->date(),
            'info' => $this->text(),
            'avatar_file_id' => $this->integer(),
            'rating' => $this->decimal(3, 2)->defaultValue(0)->notNull(),
            'city_id' => $this->integer()->notNull(),
            'phone' => $this->string(32),
            'telegram' => $this->string(64),
            'role' => $this->tinyInteger()->defaultValue(1)->notNull(),
            'status' => $this->tinyInteger(),
            'last_activity_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'failed_tasks_count' => $this->integer()->defaultValue(0)->notNull(),
            'show_only_customer' => $this->boolean()->defaultValue(0)->notNull()
        ]);

        $this->addForeignKey(
            'fk-user-city_id',
            'user',
            'city_id',
            'city',
            'id'
        );

        $this->addForeignKey(
            'fk-user-avatar_file_id',
            'user',
            'avatar_file_id',
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
            'fk-user-city_id',
            'user'
        );

        $this->dropForeignKey(
            'fk-user-avatar_file_id',
            'user'
        );

        $this->dropTable('user');
    }
}

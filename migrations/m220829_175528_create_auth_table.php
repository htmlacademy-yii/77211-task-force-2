<?php

use yii\db\Migration;

class m220829_175528_create_auth_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-auth-user_id-user-id',
            'auth',
            'user_id',
            'user',
            'id',

        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-auth-user_id-user-id',
            'auth'
        );

        $this->dropTable('auth');
    }
}

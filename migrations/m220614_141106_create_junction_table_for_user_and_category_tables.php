<?php

use yii\db\Migration;

class m220614_141106_create_junction_table_for_user_and_category_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_category', [
            'id' => $this->primaryKey()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'category_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-user_category-user_id',
            'user_category',
            'user_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-user_category-category_id',
            'user_category',
            'category_id',
            'category',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user_category-user_id',
            'user_category'
        );

        $this->dropForeignKey(
            'fk-user_category-category_id',
            'user_category'
        );

        $this->dropTable('user_category');
    }
}

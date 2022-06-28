<?php

use yii\db\Migration;

class m220628_091609_insert_categories_data_into_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            "INSERT INTO category (name, alias) VALUES ('Курьерские услуги', 'translation');
                INSERT INTO category (name, alias) VALUES ('Уборка', 'clean');
                INSERT INTO category (name, alias) VALUES ('Переезды', 'cargo');
                INSERT INTO category (name, alias) VALUES ('Компьютерная помощь', 'neo');
                INSERT INTO category (name, alias) VALUES ('Ремонт квартирный', 'flat');
                INSERT INTO category (name, alias) VALUES ('Ремонт техники', 'repair');
                INSERT INTO category (name, alias) VALUES ('Красота', 'beauty');
                INSERT INTO category (name, alias) VALUES ('Фото', 'photo');"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220628_091609_insert_categories_data_into_category_table cannot be reverted.\n";

        return false;
    }
}

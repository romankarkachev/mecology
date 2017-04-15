<?php

use yii\db\Migration;

/**
 * Создается таблица "Заявки".
 */
class m170413_095532_create_orders_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "Заявки"';
        };

        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull()->comment('Дата и время создания'),
            'seen_at' => $this->integer()->comment('Дата и время ознакомления с заявкой'),
            'form_company' => $this->string(50)->comment('Компания'),
            'form_username' => $this->string(50)->comment('Имя'),
            'form_phone' => $this->string(50)->comment('Телефон'),
            'form_email' => $this->string(50)->comment('Email'),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('orders');
    }
}

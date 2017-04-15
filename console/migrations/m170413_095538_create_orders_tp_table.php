<?php

use yii\db\Migration;

/**
 * Создается таблица "Табличные части заявок".
 */
class m170413_095538_create_orders_tp_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "Табличные части заявок"';
        };

        $this->createTable('orders_tp', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull()->comment('Заявка'),
            'hs_id' => $this->integer()->notNull()->comment('Код ТН ВЭД'),
            'weight' => $this->decimal(12,2)->notNull()->comment('Вес, кг'),
            'amount' => $this->decimal(12,2)->notNull()->comment('Сумма'),
            'formula' => $this->string()->comment('Формула расчета'),
        ], $tableOptions);

        $this->createIndex('order_id', 'orders_tp', 'order_id');
        $this->createIndex('hs_id', 'orders_tp', 'hs_id');

        $this->addForeignKey('fk_orders_tp_order_id', 'orders_tp', 'order_id', 'orders', 'id');
        $this->addForeignKey('fk_orders_tp_hs_id', 'orders_tp', 'hs_id', 'fthcdc', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_orders_tp_hs_id', 'orders_tp');
        $this->dropForeignKey('fk_orders_tp_order_id', 'orders_tp');

        $this->dropIndex('hs_id', 'orders_tp');
        $this->dropIndex('order_id', 'orders_tp');

        $this->dropTable('orders_tp');
    }
}

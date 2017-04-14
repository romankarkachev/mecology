<?php

use yii\db\Migration;

/**
 * Создается таблица "Коды ТН ВЭД".
 */
class m170413_095517_create_fthcdc_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "Коды ТН ВЭД (Foreign trade harmonised commodity description and coding)"';
        };

        $this->createTable('fthcdc', [
            'id' => $this->primaryKey(),
            'hs_code' => $this->string(12)->notNull()->comment('Код ТН ВЭД'),
            'hs_name' => $this->text()->comment('Наименование товара'),
            'hs_group' => 'TINYINT(1) COMMENT "Группа"',
            'hs_ratio' => $this->decimal(10,2)->comment('Норматив'),
            'hs_rate' => $this->decimal(10,2)->comment('Ставка (т)'),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('fthcdc');
    }
}

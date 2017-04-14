<?php

use yii\db\Migration;

/**
 * Создается таблица "Коды ФККО".
 */
class m170413_095418_create_fkko_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT ""';
        };

        $this->createTable('fkko', [
            'id' => $this->primaryKey(),
            'fkko_code' => $this->string(11)->notNull()->comment('Код по ФККО-2014'),
            'fkko_name' => $this->text()->notNull()->comment('Наименование'),
            'fkko_date' => $this->date()->comment('Дата внесения в ФККО'),
            'fkko_dc' => $this->string(5)->comment('Класс опасности'),
            'fkko2002_code' => $this->string(13)->comment('Код по ФККО-2002'),
            'fkko2002_name' => $this->text()->comment('Наименование-2002'),
            'src_id' => $this->integer(5)->comment('Код из источника'),
            'src_name' => $this->text()->comment('Наименование из источника'),
            'src_fkko' => $this->string(20)->comment('Код ФККО из источника'),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('fkko');
    }
}

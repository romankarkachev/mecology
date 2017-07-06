<?php

use yii\db\Migration;

/**
 * Создается таблица "Коды ФККО (2017)".
 */
class m170706_143059_create_fkko_2017_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "Коды ФККО (2017)"';
        };

        $this->createTable('fkko_2017', [
            'id' => $this->primaryKey(),
            'fkko_code' => $this->string(11)->notNull()->comment('Код по ФККО-2017'),
            'fkko_name' => $this->text()->notNull()->comment('Наименование'),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('fkko_2017');
    }
}

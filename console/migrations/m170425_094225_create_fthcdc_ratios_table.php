<?php

use yii\db\Migration;

/**
 * Создается таблица "Нормативы кодов ТН ВЭД по периодам".
 * Также осуществляется перенос существующих нормативов в новую таблицу.
 */
class m170425_094225_create_fthcdc_ratios_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "Нормативы кодов ТН ВЭД по периодам"';
        };

        $this->createTable('fthcdc_ratios', [
            'id' => $this->primaryKey(),
            'hs_id' => $this->integer()->notNull()->comment('Код ТН ВЭД'),
            'hs_ratio' => $this->decimal(10,3)->notNull()->comment('Норматив'),
            'year' => $this->smallInteger()->unsigned()->notNull()->comment('Год'),
        ], $tableOptions);

        $this->createIndex('hs_id', 'fthcdc_ratios', 'hs_id');

        $this->addForeignKey('fk_fthcdc_ratios_hs_id', 'fthcdc_ratios', 'hs_id', 'fthcdc', 'id');

        $ratios = \common\models\Fthcdc::find()->all();
        foreach ($ratios as $ratio) {
            /* @var $ratio \common\models\Fthcdc */
            $this->insert('fthcdc_ratios', [
                'hs_id' => $ratio->id,
                'hs_ratio' => $ratio->hs_ratio,
                'year' => 2017,
            ]);
        }

        $this->dropColumn('fthcdc', 'hs_ratio');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('fthcdc', 'hs_ratio', $this->decimal(10,2)->comment('Норматив') . ' AFTER `hs_group`');

        // переносим обратно в колонку
        $ratios = \common\models\FthcdcRatios::find()->where(['year' => 2017])->all();
        foreach ($ratios as $ratio) {
            /* @var $ratio \common\models\FthcdcRatios */
            $this->update('fthcdc', [
                'hs_ratio' => $ratio->hs_ratio,
            ], [
                'id' => $ratio->hs_id,
            ]);
        }

        $this->dropForeignKey('fk_fthcdc_ratios_hs_id', 'fthcdc_ratios');

        $this->dropIndex('hs_id', 'fthcdc_ratios');

        $this->dropTable('fthcdc_ratios');
    }
}

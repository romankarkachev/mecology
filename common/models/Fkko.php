<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fkko".
 *
 * @property integer $id
 * @property string $fkko_code
 * @property string $fkko_name
 * @property string $fkko_date
 * @property string $fkko_dc
 * @property string $fkko2002_code
 * @property string $fkko2002_name
 * @property integer $src_id
 * @property string $src_name
 * @property string $src_fkko
 */
class Fkko extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fkko';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fkko_code', 'fkko_name'], 'required'],
            [['fkko_name', 'fkko2002_name', 'src_name'], 'string'],
            [['fkko_date'], 'safe'],
            [['src_id'], 'integer'],
            [['fkko_code'], 'string', 'max' => 11],
            [['fkko_dc'], 'string', 'max' => 5],
            [['fkko2002_code'], 'string', 'max' => 13],
            [['src_fkko'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fkko_code' => 'Код по ФККО-2014',
            'fkko_name' => 'Наименование',
            'fkko_date' => 'Дата внесения в ФККО',
            'fkko_dc' => 'Класс опасности',
            'fkko2002_code' => 'Код по ФККО-2002',
            'fkko2002_name' => 'Наименование-2002',
            'src_id' => 'Код из источника',
            'src_name' => 'Наименование из источника',
            'src_fkko' => 'Код ФККО из источника',
        ];
    }
}

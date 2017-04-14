<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fthcdc".
 *
 * @property integer $id
 * @property string $hs_code
 * @property string $hs_name
 * @property integer $hs_group
 * @property string $hs_ratio Норматив
 * @property string $hs_rate Ставка
 */
class Fthcdc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fthcdc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hs_code'], 'required'],
            [['hs_name'], 'string'],
            [['hs_group'], 'integer', 'min' => 0],
            [['hs_ratio', 'hs_rate'], 'number', 'min' => 0],
            [['hs_code'], 'string', 'max' => 12],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hs_code' => 'Код ТН ВЭД',
            'hs_name' => 'Наименование товара',
            'hs_group' => 'Группа',
            'hs_ratio' => 'Норматив',
            'hs_rate' => 'Ставка', // за тонну
        ];
    }
}

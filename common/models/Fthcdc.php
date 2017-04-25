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
 * @property string $hs_rate Ставка
 *
 * @property FthcdcRatios[] $fthcdcRatios
 * @property FthcdcRatios $fthcdcRatiosCurrentYear
 * @property OrdersTp[] $ordersTps
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
            [['hs_rate'], 'number', 'min' => 0],
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
            'fthcdcCurrentRatio' => 'Норматив',
            'hs_rate' => 'Ставка', // за тонну
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFthcdcRatios()
    {
        return $this->hasMany(FthcdcRatios::className(), ['hs_id' => 'id']);
    }

    /**
     * Возвращает одну запись с условием за текущий год.
     * @return \yii\db\ActiveQuery
     */
    public function getFthcdcRatiosCurrentYear()
    {
        return $this->hasOne(FthcdcRatios::className(), ['hs_id' => 'id'])
            ->andWhere(['year' => date('Y')]);
    }

    /**
     * Возвращает текущую ставку.
     * @return float
     */
    public function getFthcdcCurrentRatio()
    {
        return $this->fthcdcRatiosCurrentYear == null ? 0 : $this->fthcdcRatiosCurrentYear->hs_ratio;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersTps()
    {
        return $this->hasMany(OrdersTp::className(), ['hs_id' => 'id']);
    }
}

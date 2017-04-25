<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fthcdc_ratios".
 *
 * @property integer $id
 * @property integer $hs_id
 * @property string $hs_ratio
 * @property integer $year
 *
 * @property Fthcdc $hs
 */
class FthcdcRatios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fthcdc_ratios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hs_id', 'hs_ratio', 'year'], 'required'],
            [['hs_id', 'year'], 'integer'],
            [['hs_ratio'], 'number'],
            [['hs_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fthcdc::className(), 'targetAttribute' => ['hs_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hs_id' => 'Код ТН ВЭД',
            'hs_ratio' => 'Норматив',
            'year' => 'Год',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHs()
    {
        return $this->hasOne(Fthcdc::className(), ['id' => 'hs_id']);
    }
}

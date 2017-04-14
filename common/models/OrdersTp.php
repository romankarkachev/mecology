<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "orders_tp".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $hs_id
 * @property integer $weight
 * @property integer $amount
 * @property string $formula
 *
 * @property Orders $order
 * @property Fthcdc $hs
 */
class OrdersTp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_tp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'hs_id', 'weight', 'amount'], 'required'],
            [['order_id', 'hs_id', 'weight', 'amount'], 'integer'],
            [['formula'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
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
            'order_id' => 'Заявка',
            'hs_id' => 'Код ТН ВЭД',
            'weight' => 'Вес, кг',
            'amount' => 'Сумма',
            'formula' => 'Формула расчета',
            // для сортировки
            'hsCode' => 'Код ТН ВЭД',
            'hsName' => 'Наименование',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHs()
    {
        return $this->hasOne(Fthcdc::className(), ['id' => 'hs_id']);
    }

    /**
     * Возвращает код ТН ВЭД.
     * @return string
     */
    public function getHsCode()
    {
        return $this->hs != null ? $this->hs->hs_code : '';
    }

    /**
     * Возвращает наименование ТН ВЭД.
     * @return string
     */
    public function getHsName()
    {
        return $this->hs != null ? $this->hs->hs_name : '';
    }
}

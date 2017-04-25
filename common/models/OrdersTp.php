<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "orders_tp".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $hs_id
 * @property string $weight
 * @property float $hs_ratio
 * @property string $amount
 * @property string $formula
 *
 * @property Orders $order
 * @property Fthcdc $hs
 */
class OrdersTp extends \yii\db\ActiveRecord
{
    /**
     * Норматив, в базе не сохраняется, необходим для составления формулы
     * @var float
     */
    public $hs_ratio;

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
            [['order_id', 'hs_id'], 'integer'],
            [['weight', 'hs_ratio', 'amount'], 'number'],
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
            'hs_ratio' => 'Норматив',
            'amount' => 'Сумма',
            'formula' => 'Формула расчета',
            // для сортировки
            'hsCode' => 'Код ТН ВЭД',
            'hsName' => 'Наименование',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // перед сохранением (то есть, когда уже успешно пройдена валидация)
            // если заполнен код тн вэд (несмотря на то, что это поле обязательное)
            if ($insert && $this->hs != null)
                // составим формулу, по которой рассчитана сумма
                $this->formula = Yii::$app->formatter->asDecimal($this->weight / 1000, 5) . ' т × ' . Yii::$app->formatter->asDecimal($this->hs_ratio) . ' × ' . Yii::$app->formatter->asDecimal($this->hs->hs_rate);
            return true;
        }
        return false;
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
        return $this->hs != null ? ($this->hs->hs_name == null || $this->hs->hs_name == '' ? $this->hs->hs_code : $this->hs->hs_code . ' - ' . $this->hs->hs_name) : '';
    }
}

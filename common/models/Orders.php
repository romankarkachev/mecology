<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $seen_at
 * @property string $form_company
 * @property string $form_username
 * @property string $form_phone
 * @property string $form_email
 *
 * @property OrdersTp[] $ordersTps
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'seen_at'], 'integer'],
            [['form_company', 'form_username', 'form_phone', 'form_email'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата и время создания',
            'seen_at' => 'Дата и время ознакомления с заявкой',
            'form_company' => 'Компания',
            'form_username' => 'Имя',
            'form_phone' => 'Телефон',
            'form_email' => 'Email',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                // убираем из номера телефона все символы кроме цифр
                $this->form_phone = preg_replace("/[^0-9]/", '', $this->form_phone);
            }

            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // удалим табличную часть
            OrdersTp::deleteAll(['order_id' => $this->id]);

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersTps()
    {
        return $this->hasMany(OrdersTp::className(), ['order_id' => 'id']);
    }
}

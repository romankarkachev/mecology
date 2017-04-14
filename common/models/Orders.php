<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $created_at
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
            [['created_at'], 'integer'],
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
            'form_company' => 'Компания',
            'form_username' => 'Имя',
            'form_phone' => 'Телефон',
            'form_email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersTps()
    {
        return $this->hasMany(OrdersTp::className(), ['order_id' => 'id']);
    }
}

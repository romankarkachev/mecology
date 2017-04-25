<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Orders;
use common\models\OrdersTp;

/**
 * CalculatorForm форма калькулятора.
 */
class CalculatorForm extends Model
{
    /**
     * Поле формы Название компании
     * @var string
     */
    public $form_company;

    /**
     * Поле формы Ваше имя
     * @var string
     */
    public $form_username;

    /**
     * Поле формы Номер телефона
     * @var string
     */
    public $form_phone;

    /**
     * Поле формы E-mail
     * @var string
     */
    public $form_email;

    /**
     * Год, за который запрашиваются нормативы
     * @var integer
     */
    public $year;

    /**
     * Табличная часть
     * @var array
     */
    public $tp;

    /**
     * Массив ошибок при заполнении табличной части
     * @var array
     */
    public $tp_errors;

    /**
     * Контрольные символы
     * @var string
     */
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_company', 'form_username', 'form_phone'], 'required'],
            ['form_email', 'email'],
            ['verifyCode', 'captcha', 'captchaAction' => 'default/captcha'],
            // собственные правила валидации
            ['form_phone', 'validatePhone'],
            ['tp', 'validateTablePart'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'form_company' => 'Название компании',
            'form_username' => 'Ваше имя',
            'form_phone' => 'Номер телефона',
            'form_email' => 'E-mail',
            'year' => 'Год',
            'verifyCode' => 'Введите символы',
        ];
    }

    /**
     * Собственное правило валидации для номера телефона.
     */
    public function validatePhone()
    {
        $phone_processed = preg_replace("/[^0-9]/", '', $this->form_phone);
        if (strlen($phone_processed) < 10)
            $this->addError('form_phone', 'Номер телефона должен состоять из 10 цифр.');
    }

    /**
     * Собственное правило валидации для табличной части документа.
     */
    public function validateTablePart()
    {
        if (count($this->tp) > 0) {
            $row_numbers = [];
            // вводится отдельный итератор, потому что строки могут иметь совсем не свои номера
            $iterator = 1;
            foreach ($this->tp as $item) {
                $oa = new OrdersTp();
                $oa->attributes  = $item;
                if (!$oa->validate(['hs_id', 'weight', 'amount'])) {
                    $row_numbers[] = $iterator;
                }
                $iterator++;
            }
            if (count($row_numbers) > 0) $this->addError('tp_errors', 'Не все обязательные поля в табличной части заполнены! Строки: '.implode(',', $row_numbers).'.');
        }
    }

    /**
     * Превращает данные из массива идентификаторов в массив моделей OrdersTp.
     * @return array
     */
    public function makeTpModelsFromPostArray()
    {
        $result = [];
        if (is_array($this->tp)) if (count($this->tp) > 0) {
            foreach ($this->tp as $item) {
                $dtp = new OrdersTp();
                $dtp->attributes = $item;
                $dtp->hs_ratio = $item['hs_ratio'];
                // из-за особенностей виджета, домножаем на 1000 введенный вес
                $dtp->weight = $dtp->weight * 1000;
                $result[] = $dtp;
            }
        }
        return $result;
    }

    /**
     * Выполняет создание заявки на основании данных формы.
     * @var $tp \common\models\OrdersTp[] массив моделей строк табличной части
     * @return bool
     */
    public function createOrder($tp)
    {
        $order = new Orders();
        $order->attributes = $this->attributes;
        if ($order->save()) {
            // если заявка успешно создана, дополним ее табличной частью
            foreach ($tp as $row) {
                $otp = new OrdersTp();
                $otp->attributes = $row->attributes;
                $otp->hs_ratio = $row['hs_ratio'];
                $otp->order_id = $order->id;
                $otp->save();
            }

            // отправляем уведомление менеджеру о том, что создана заявка
            $this->sendEmail($order);

            return true;
        }
        return false;
    }

    /**
     * Выполняет отправку уведомления о создании новой заявки.
     * @param $order Orders только что созданная заявка
     */
    public function sendEmail($order)
    {
        $params['order'] = $order;

        Yii::$app->mailer->compose([
            'html' => 'newOrderHasBeenCreated-html',
        ], $params)
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setTo(Yii::$app->params['operatorEmail'])
            ->setSubject('На сайте ' . Yii::$app->name . ' создана новая заявка')
            ->send();
    }
}

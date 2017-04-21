<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm - это модель для формы обратной связи (пункт меню "Пожаловаться").
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'subject', 'body'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'captcha', 'captchaAction' => 'default/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Ваш E-mail',
            'subject' => 'Тема обращения',
            'body' => 'Текст обращения',
            'verifyCode' => 'Введите символы',
        ];
    }

    /**
     * Отправляет письмо посетителя сайта на почтовый ящик администратора.
     * @return boolean успешно ли отправлено письмо
     */
    public function sendEmail()
    {
        $params = [
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'body' => $this->body,
        ];

        return Yii::$app->mailer->compose([
            'html' => 'contact-html',
        ], $params)
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setReplyTo([$this->email => $this->name])
            ->setTo(Yii::$app->params['operatorEmail'])
            ->setSubject('Пользователь сайта ' . Yii::$app->name . ' обращается к администрации сайта')
            ->send();
    }
}

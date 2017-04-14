<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\components\grid;

use Yii;
use yii\helpers\Html;
use yii\grid\ActionColumn as BaseActionColumn;

/**
 * Собственное расширение для вывода кнопок с действиями в таблицах.
 * @author Roman Karkachev <post@romankarkachev.ru>
 * @since 2.0
 */
class ActionColumn extends BaseActionColumn
{
    /**
     * @inheritdoc
     */
    public $template = '{update} {delete}';

    /**
     * @var array html options to be applied to the [[initDefaultButton()|default button]].
     * @since 2.0.4
     */
    public $buttonOptions = [
        'class' => 'btn btn-sm',
    ];

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('update', 'pencil', [
            'class' => 'btn-secondary',
        ]);
        $this->initDefaultButton('delete', 'trash-o', [
            'class' => 'btn-danger',
            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'data-method' => 'post',
        ]);
    }

    /**
     * Initializes the default button rendering callback for single button
     * @param string $name Button name as it's written in template
     * @param string $iconName The part of Bootstrap glyphicon class that makes it unique
     * @param array $additionalOptions Array of additional options
     * @since 2.0.11
     */
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = Yii::t('yii', 'View');
                        break;
                    case 'update':
                        $title = Yii::t('yii', 'Update');
                        break;
                    case 'delete':
                        $title = Yii::t('yii', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }

                $buttonOptions = $this->buttonOptions;
                if (isset($additionalOptions['class'])) {
                    $this->buttonOptions['class'] .= ' ' . $additionalOptions['class'];
                    //unset($additionalOptions['class']);
                }

                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);
                $this->buttonOptions = $buttonOptions;
                $icon = Html::tag('i', '', ['class' => "fa fa-$iconName"]);
                return Html::a($icon, $url, $options);
            };
        }
    }
}
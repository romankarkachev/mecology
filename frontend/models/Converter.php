<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use common\models\FkkoConverter;

/**
 * Converter represents the model behind the search form about `common\models\FkkoConverter`.
 */
class Converter extends FkkoConverter
{
    /**
     * Режимы поиска.
     */
    const SEARCH_MODE_2014 = 1;
    const SEARCH_MODE_2002 = 2;

    /**
     * Поле отбора для выбора режима поиска.
     * @var integer
     */
    public $searchMode;

    /**
     * Поле отбора для универсального поиска (во всем полям).
     * @var string
     */
    public $searchEntire;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['searchMode'], 'integer'],
            [['searchEntire'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        $labels['searchMode'] = 'Режим';
        $labels['searchEntire'] = 'Значение для поиска по всем полям';

        return $labels;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Возвращает в виде массива разновидности режимов отбора.
     * @return array
     */
    public static function fetchModes()
    {
        return [
            [
                'id' => self::SEARCH_MODE_2014,
                'name' => 'Поиск по кодам 2014',
            ],
            [
                'id' => self::SEARCH_MODE_2002,
                'name' => 'Поиск по кодам 2002',
            ],
        ];
    }

    /**
     * Делает выборку статусов клиентов и возвращает в виде массива.
     * Применяется для вывода в виджетах Select2.
     * @return array
     */
    public static function arrayMapOfModesForSelect2()
    {
        return ArrayHelper::map(self::fetchModes() , 'id', 'name');
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = FkkoConverter::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
                'route' => 'fkko',
            ],
            'sort' => [
                'route' => 'fkko',
                'defaultOrder' => ['fkko_code' => SORT_ASC]
            ]
        ]);

        if (!($this->load($params) && $this->validate()) || (empty($this->searchEntire) || empty($this->searchMode))) {
            // если пользователь ничего не ввел или валидация провалилась с треском, возвращаем пустую выборку
            $query->where('1 <> 1');
            return $dataProvider;
        }

        switch ($this->searchMode) {
            case self::SEARCH_MODE_2014:
                $query->orFilterWhere(['like', 'fkko_code', $this->searchEntire])
                    ->orFilterWhere(['like', 'fkko_name', $this->searchEntire]);
                break;
            case self::SEARCH_MODE_2002:
                $query->orFilterWhere(['like', 'fkko2002_code', $this->searchEntire])
                    ->orFilterWhere(['like', 'fkko2002_name', $this->searchEntire]);
                break;
        }

        return $dataProvider;
    }
}

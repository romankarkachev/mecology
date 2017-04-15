<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fkko;

/**
 * FkkoSearch represents the model behind the search form about `common\models\Fkko`.
 */
class FkkoSearch extends Fkko
{
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
            [['id', 'src_id'], 'integer'],
            [['fkko_code', 'fkko_name', 'fkko_date', 'fkko_dc', 'fkko2002_code', 'fkko2002_name', 'src_name', 'src_fkko', 'searchEntire'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Fkko::find();
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

        if (!($this->load($params) && $this->validate()) || (empty($this->searchEntire))) {
            // если пользователь ничего не ввел или валидация провалилась с треском, возвращаем пустую выборку
            $query->where('1 <> 1');
            return $dataProvider;
        }

        $query->orFilterWhere(['like', 'fkko_code', $this->searchEntire])
            ->orFilterWhere(['like', 'fkko_name', $this->searchEntire]);

        return $dataProvider;
    }
}

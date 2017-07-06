<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FkkoConverter;

/**
 * FkkoConverterSearch represents the model behind the search form about `common\models\FkkoConverter`.
 */
class FkkoConverterSearch extends FkkoConverter
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
        $query = FkkoConverter::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
                'route' => 'fkko-converter',
            ],
            'sort' => [
                'route' => 'fkko-converter',
                'defaultOrder' => ['fkko_code' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'fkko_date' => $this->fkko_date,
            'src_id' => $this->src_id,
        ]);

        if ($this->searchEntire != null)
            $query->andFilterWhere([
                'or',
                ['like', 'fkko_code', $this->searchEntire],
                ['like', 'fkko_name', $this->searchEntire],
                ['like', 'fkko2002_code', $this->searchEntire],
                ['like', 'fkko2002_name', $this->searchEntire]
            ]);
        else
            $query->andFilterWhere(['like', 'fkko_code', $this->fkko_code])
                ->andFilterWhere(['like', 'fkko_name', $this->fkko_name])
                ->andFilterWhere(['like', 'fkko_dc', $this->fkko_dc])
                ->andFilterWhere(['like', 'fkko2002_code', $this->fkko2002_code])
                ->andFilterWhere(['like', 'fkko2002_name', $this->fkko2002_name])
                ->andFilterWhere(['like', 'src_name', $this->src_name])
                ->andFilterWhere(['like', 'src_fkko', $this->src_fkko]);

        return $dataProvider;
    }
}

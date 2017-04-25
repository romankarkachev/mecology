<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fthcdc;

/**
 * FthcdcSearch represents the model behind the search form about `common\models\Fthcdc`.
 */
class FthcdcSearch extends Fthcdc
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
            [['id', 'hs_group'], 'integer'],
            [['hs_code', 'hs_name', 'searchEntire'], 'safe'],
            [['hs_rate'], 'number'],
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
        $query = Fthcdc::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
                'route' => 'fthcdc',
            ],
            'sort' => [
                'route' => 'fthcdc',
                'defaultOrder' => ['hs_code' => SORT_ASC],
                'attributes' => [
                    'id',
                    'hs_code',
                    'hs_name',
                    'hs_group',
                    'fthcdcCurrentRatio' => [
                        'asc' => ['fthcdc_ratios.hs_ratio' => SORT_ASC],
                        'desc' => ['fthcdc_ratios.hs_ratio' => SORT_DESC],
                    ],
                    'hs_rate',
                ],
            ]
        ]);

        $this->load($params);
        $query->joinWith(['fthcdcRatiosCurrentYear']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'hs_group' => $this->hs_group,
            'hs_rate' => $this->hs_rate,
        ]);

        if ($this->searchEntire != null)
            $query->orFilterWhere(['like', 'hs_code', $this->searchEntire])
                ->orFilterWhere(['like', 'hs_name', $this->searchEntire]);
        else
            $query->andFilterWhere(['like', 'hs_code', $this->hs_code])
                ->andFilterWhere(['like', 'hs_name', $this->hs_name]);

        return $dataProvider;
    }
}

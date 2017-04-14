<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrdersTp;

/**
 * OrdersTpSearch represents the model behind the search form about `common\models\OrdersTp`.
 */
class OrdersTpSearch extends OrdersTp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'hs_id', 'weight', 'amount', 'formula'], 'integer'],
        ];
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
        $query = OrdersTp::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'hs_id',
                'weight',
                'amount',
                'hsCode' => [
                    'asc' => ['fthcdc.hs_code' => SORT_ASC],
                    'desc' => ['fthcdc.hs_code' => SORT_DESC],
                ],
                'hsName' => [
                    'asc' => ['fthcdc.hs_name' => SORT_ASC],
                    'desc' => ['fthcdc.hs_name' => SORT_DESC],
                ],
            ]
        ]);

        $this->load($params);
        $query->joinWith(['order', 'hs']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'hs_id' => $this->hs_id,
            'weight' => $this->weight,
            'amount' => $this->amount,
            'formula' => $this->formula,
        ]);

        return $dataProvider;
    }
}

<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Orders;

/**
 * OrdersSearch represents the model behind the search form about `common\models\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * Поле отбора, определяющее начало периода.
     * @var string
     */
    public $searchDateStart;

    /**
     * Поле отбора, определяющее окончания периода.
     * @var string
     */
    public $searchDateEnd;

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
            [['id', 'created_at'], 'integer'],
            [['form_company', 'form_username', 'form_phone', 'form_email', 'searchEntire'], 'safe'],
            // для отбора
            [['searchDateStart', 'searchDateEnd'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        $labels['searchDateStart'] = 'Начало периода';
        $labels['searchDateEnd'] = 'Конец периода';
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
        $query = Orders::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
                'route' => 'orders',
            ],
            'sort' => [
                'route' => 'orders',
                'defaultOrder' => ['created_at' => SORT_DESC]
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
        ]);

        if ($this->searchDateStart !== null or $this->searchDateEnd !== null)
            if ($this->searchDateStart !== '' && $this->searchDateEnd !== '') {
                // если указаны обе даты
                $query->andFilterWhere(['between', '`orders`.`created_at`', strtotime($this->searchDateStart.' 00:00:00'), strtotime($this->searchDateEnd.' 23:59:59')]);
            }
            else if ($this->searchDateStart !== '' && $this->searchDateEnd === '') {
                // если указан только начало периода
                $query->andFilterWhere(['>=','`orders`.`created_at`', strtotime($this->searchDateStart.' 00:00:00')]);
            }
            else if ($this->searchDateStart === '' && $this->searchDateEnd !== '') {
                // если указан только конец периода
                $query->andFilterWhere(['<=', '`orders`.`created_at`', strtotime($this->searchDateEnd.' 23:59:59')]);
            };

        if ($this->searchEntire != null)
            $query->orFilterWhere(['like', 'form_company', $this->searchEntire])
                ->orFilterWhere(['like', 'form_username', $this->searchEntire])
                ->orFilterWhere(['like', 'form_phone', $this->searchEntire])
                ->orFilterWhere(['like', 'form_email', $this->searchEntire]);
        else
            $query->andFilterWhere(['like', 'form_company', $this->form_company])
                ->andFilterWhere(['like', 'form_username', $this->form_username])
                ->andFilterWhere(['like', 'form_phone', $this->form_phone])
                ->andFilterWhere(['like', 'form_email', $this->form_email]);

        return $dataProvider;
    }
}

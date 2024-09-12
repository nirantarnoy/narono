<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Employeefine;

/**
 * EmployeefineSearch represents the model behind the search form of `backend\models\Employeefine`.
 */
class EmployeefineSearch extends Employeefine
{
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'car_id', 'emp_id', 'customer_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['case_no', 'trans_date', 'place', 'cause_description'], 'safe'],
            [['globalSearch'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Employeefine::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'trans_date' => $this->trans_date,
            'company_id' => $this->company_id,
            'car_id' => $this->car_id,
            'emp_id' => $this->emp_id,
            'customer_id' => $this->customer_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        if ($this->globalSearch != null) {
            $query->orFilterWhere(['like', 'case_no', $this->globalSearch])
                ->orFilterWhere(['like', 'place', $this->globalSearch])
                ->orFilterWhere(['like', 'cause_description', $this->globalSearch]);
        }


        return $dataProvider;
    }
}

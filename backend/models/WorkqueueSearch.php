<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Workqueue;

/**
 * WorkqueueSearch represents the model behind the search form of `backend\models\Workqueue`.
 */
class WorkqueueSearch extends Workqueue
{
    /**
     * {@inheritdoc}
     */

    public $globalSearch;
    public $car_type_id;

    public function rules()
    {
        return [
            [['id', 'customer_id', 'emp_assign', 'status', 'create_at', 'created_by', 'updated_at', 'updated_by','car_id','company_id','car_type_id'], 'integer'],
            [['work_queue_no', 'work_queue_date', 'dp_no'], 'safe'],
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
        $query = Workqueue::find()->joinWith(['customer','car'])
            ->leftJoin('employee','work_queue.emp_assign=employee.id');

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
            'work_queue.id' => $this->id,
            'work_queue.customer_id' => $this->customer_id,
            'work_queue.emp_assign' => $this->emp_assign,
            'work_queue.status' => $this->status,
            'work_queue.car_id' => $this->car_id,
            'work_queue.company_id' => $this->company_id,
            'car.car_type_id' => $this->car_type_id,
        ]);

        if(!empty($this->work_queue_date)){
            $query->andFilterWhere(['like', 'work_queue_date', date('Y-m-d', strtotime($this->work_queue_date))]);
        }

        $query->andFilterWhere(['or',
            ['like', 'work_queue_no', $this->globalSearch],
            ['like', 'dp_no', $this->globalSearch],
            ['like', 'customer.name', $this->globalSearch],
            ['like', 'employee.fname', $this->globalSearch]
        ]);

        return $dataProvider;
    }
}

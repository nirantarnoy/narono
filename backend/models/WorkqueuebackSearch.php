<?php

namespace backend\models;

use backend\models\Workqueueback;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WorkqueueSearch represents the model behind the search form of `backend\models\Workqueue`.
 */
class WorkqueuebackSearch extends Workqueueback
{
    /**
     * {@inheritdoc}
     */

    public $globalSearch;

    public function rules()
    {
        return [
            [['id', 'customer_id', 'emp_assign', 'status', 'create_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
        $query = Workqueueback::find()->leftJoin('customer','work_queue.customer_id = customer.id')->leftJoin('employee','work_queue.emp_assign=employee.id');

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
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'work_queue_date' => $this->work_queue_date,
//            'customer_id' => $this->customer_id,
//            'emp_assign' => $this->emp_assign,
//            'status' => $this->status,
//            'create_at' => $this->create_at,
//            'created_by' => $this->created_by,
//            'updated_at' => $this->updated_at,
//            'updated_by' => $this->updated_by,
//        ]);

        $query->orFilterWhere(['like', 'work_queue_no', $this->globalSearch])
            ->orFilterWhere(['like', 'dp_no', $this->globalSearch])
            ->orFilterWhere(['like', 'customer.name', $this->globalSearch])
            ->orFilterWhere(['like', 'employee.fname', $this->globalSearch]);

        return $dataProvider;
    }
}

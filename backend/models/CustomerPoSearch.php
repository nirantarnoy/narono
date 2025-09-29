<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CustomerPoSearch represents the model behind the search form of `backend\models\CustomerPo`.
 */
class CustomerPoSearch extends CustomerPo
{
    public $customer_name;
    public $po_date_from;
    public $po_date_to;
    public $po_target_date_from;
    public $po_target_date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'created_by', 'updated_by'], 'integer'],
            [['po_number', 'po_date', 'po_target_date', 'work_name', 'po_file', 'status', 'remark', 'customer_name', 'po_date_from', 'po_date_to', 'po_target_date_from', 'po_target_date_to'], 'safe'],
            [['po_amount', 'billed_amount', 'remaining_amount'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
        $query = CustomerPo::find();

        // add conditions that should always apply here
        $query->joinWith(['customer']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'po_date' => SORT_DESC,
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        // Enable sorting by customer name
        $dataProvider->sort->attributes['customer_name'] = [
            'asc' => ['customer.name' => SORT_ASC],
            'desc' => ['customer.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'customer_po.id' => $this->id,
            'customer_po.customer_id' => $this->customer_id,
            'customer_po.po_amount' => $this->po_amount,
            'customer_po.billed_amount' => $this->billed_amount,
            'customer_po.remaining_amount' => $this->remaining_amount,
            'customer_po.status' => $this->status,
            'customer_po.created_by' => $this->created_by,
            'customer_po.updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'customer_po.po_number', $this->po_number])
            ->andFilterWhere(['like', 'customer_po.work_name', $this->work_name])
            ->andFilterWhere(['like', 'customer_po.po_file', $this->po_file])
            ->andFilterWhere(['like', 'customer_po.remark', $this->remark])
            ->andFilterWhere(['like', 'customer.name', $this->customer_name]);

        // Date range filters
        if (!empty($this->po_date_from)) {
            $query->andFilterWhere(['>=', 'customer_po.po_date', $this->po_date_from]);
        }
        if (!empty($this->po_date_to)) {
            $query->andFilterWhere(['<=', 'customer_po.po_date', $this->po_date_to]);
        }
        if (!empty($this->po_target_date_from)) {
            $query->andFilterWhere(['>=', 'customer_po.po_target_date', $this->po_target_date_from]);
        }
        if (!empty($this->po_target_date_to)) {
            $query->andFilterWhere(['<=', 'customer_po.po_target_date', $this->po_target_date_to]);
        }

        // Single date filters (if date range not used)
        if (empty($this->po_date_from) && empty($this->po_date_to)) {
            $query->andFilterWhere(['like', 'customer_po.po_date', $this->po_date]);
        }
        if (empty($this->po_target_date_from) && empty($this->po_target_date_to)) {
            $query->andFilterWhere(['like', 'customer_po.po_target_date', $this->po_target_date]);
        }

        return $dataProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'customer_name' => 'ชื่อลูกค้า',
            'po_date_from' => 'วันที่สร้าง PO (จาก)',
            'po_date_to' => 'วันที่สร้าง PO (ถึง)',
            'po_target_date_from' => 'วันที่หมดอายุ (จาก)',
            'po_target_date_to' => 'วันที่หมดอายุ (ถึง)',
        ]);
    }
}
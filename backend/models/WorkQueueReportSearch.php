<?php

namespace backend\models;

use backend\models\Workqueue;
use backend\models\Workqueue;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Workqueue;

/**
 * WorkQueueReportSearch represents the model behind the search form for work queue report.
 */
class WorkQueueReportSearch extends Workqueue
{
    public $start_date;
    public $end_date;
    public $customer_id;
    public $customer_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_date', 'end_date'], 'safe'],
            [['customer_id'], 'integer'],
            [['customer_name'], 'safe'],
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
     */
    public function search($params)
    {
        $query = Workqueue::find()
            ->joinWith(['workQueueDropoff', 'customer'])
            ->groupBy(['work_queue.id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'work_queue_date' => SORT_ASC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filter by date range
        if ($this->start_date) {
            $query->andWhere(['>=', 'work_queue.work_queue_date', $this->start_date]);
        }

        if ($this->end_date) {
            $query->andWhere(['<=', 'work_queue.work_queue_date', $this->end_date]);
        }

        // Filter by customer
        if ($this->customer_id) {
            $query->andWhere(['work_queue.customer_id' => $this->customer_id]);
        }

        return $dataProvider;
    }

    /**
     * Get report data for date range 1-15 of month
     */
    public function getReportData($params)
    {
        $this->load($params);

        // Default to current month if not specified
        if (!$this->start_date) {
            $this->start_date = date('Y-m-01');
        }
        if (!$this->end_date) {
            $this->end_date = date('Y-m-15');
        }

//        $query = WorkQueue::find()
//            ->select([
//                'work_queue.*',
//                'customer.name as customer_name',
//                'customer.code as customer_code',
//                'work_queue_dropoff.dropoff_id',
//                'customer_po.po_number',
//                'SUM(work_queue_dropoff.qty) as total_qty',
//                'SUM(work_queue_dropoff.weight) as total_weight',
//                'SUM(work_queue_dropoff.price_per_ton) as total_price_per_ton',
//                'SUM(work_queue_dropoff.price_line_total) as total_price',
//            ])
//            ->joinWith(['workQueueDropoff', 'customer','customerPo'])
//            ->where(['>=', 'work_queue.work_queue_date', $this->start_date])
//            ->andWhere(['<=', 'work_queue.work_queue_date', $this->end_date])
//            ->groupBy(['work_queue.id']);
        $query = WorkQueue::find()
            ->select([
                'work_queue.*',
                'customer.name as customer_name',
                'customer.code as customer_code',
                'work_queue_dropoff.dropoff_id',
                'customer_po.po_number',
                'work_queue_dropoff.qty as total_qty',
                'work_queue_dropoff.weight as total_weight',
                'work_queue_dropoff.price_per_ton as total_price_per_ton',
                'work_queue_dropoff.price_line_total as total_price',
            ])
            ->joinWith(['workQueueDropoff', 'customer','customerPo'])
            ->where(['>=', 'work_queue.work_queue_date', $this->start_date])
            ->andWhere(['<=', 'work_queue.work_queue_date', $this->end_date])
            ->groupBy(['work_queue.id']);

        if ($this->customer_id) {
            $query->andWhere(['work_queue.customer_id' => $this->customer_id]);
        }

        $results = $query->asArray()->all();

        return $results;
    }

    /**
     * Get summary data for the report
     */
    public function getSummaryData($params)
    {
        $this->load($params);

        if (!$this->start_date) {
            $this->start_date = date('Y-m-01');
        }
        if (!$this->end_date) {
            $this->end_date = date('Y-m-15');
        }

        $query = WorkQueue::find()
            ->select([
                'SUM(work_queue_dropoff.qty) as grand_total_qty',
                'SUM(work_queue_dropoff.weight) as grand_total_weight',
                'SUM(work_queue_dropoff.price_line_total) as grand_total_price',
            ])
            ->joinWith(['workQueueDropoff'])
            ->where(['>=', 'work_queue.work_queue_date', $this->start_date])
            ->andWhere(['<=', 'work_queue.work_queue_date', $this->end_date]);

        if ($this->customer_id) {
            $query->andWhere(['work_queue.customer_id' => $this->customer_id]);
        }

        return $query->asArray()->one();
    }
}
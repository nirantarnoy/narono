<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ActionLog;

/**
 * ActionLogSearch represents the search model for `common\models\ActionLog`.
 */
class ActionLogSearch extends ActionLog
{
    public $globalSearch;
    public $from_date;
    public $to_date;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at'], 'integer'],
            [['action', 'table_name', 'model_class', 'record_id', 'record_name', 'username', 'description', 'globalSearch', 'from_date', 'to_date', 'ip_address'], 'safe'],
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ActionLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'action' => $this->action,
        ]);

        $query->andFilterWhere(['like', 'table_name', $this->table_name])
            ->andFilterWhere(['like', 'record_id', $this->record_id])
            ->andFilterWhere(['like', 'record_name', $this->record_name])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'description', $this->description]);

        if (!empty($this->from_date)) {
            $query->andWhere(['>=', 'created_at', strtotime($this->from_date . ' 00:00:00')]);
        }
        if (!empty($this->to_date)) {
            $query->andWhere(['<=', 'created_at', strtotime($this->to_date . ' 23:59:59')]);
        }

        if (!empty($this->globalSearch)) {
            $query->andWhere([
                'or',
                ['like', 'username', $this->globalSearch],
                ['like', 'table_name', $this->globalSearch],
                ['like', 'record_id', $this->globalSearch],
                ['like', 'record_name', $this->globalSearch],
                ['like', 'description', $this->globalSearch],
                ['like', 'raw_data_before', $this->globalSearch],
                ['like', 'raw_data_after', $this->globalSearch],
            ]);
        }

        return $dataProvider;
    }
}


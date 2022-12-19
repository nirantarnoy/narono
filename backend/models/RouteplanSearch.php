<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Routeplan;

/**
 * RouteplanSearch represents the model behind the search form of `backend\models\Routeplan`.
 */
class RouteplanSearch extends Routeplan
{
    /**
     * {@inheritdoc}
     */
    public $globalSearch;

    public function rules()
    {
        return [
            [['id', 'des_province_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['des_name'], 'safe'],
            [['total_distanct'], 'number'],
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
        $query = Routeplan::find();

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
//            'des_province_id' => $this->des_province_id,
//            'total_distanct' => $this->total_distanct,
//            'status' => $this->status,
//            'created_at' => $this->created_at,
//            'created_by' => $this->created_by,
//            'updated_at' => $this->updated_at,
//            'updated_by' => $this->updated_by,
//        ]);

        $query->orFilterWhere(['like', 'des_name', $this->globalSearch]);

        return $dataProvider;
    }
}

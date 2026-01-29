<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Vendor;

/**
 * VendorSearch represents the model behind the search form of `backend\models\Vendor`.
 */
class VendorSearch extends Vendor
{
    public $globalSearch;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'vendor_group_id', 'payment_term_id', 'payment_method_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['code', 'name', 'description'], 'safe'],
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
        $query = Vendor::find();

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
            'vendor_group_id' => $this->vendor_group_id,
            'payment_term_id' => $this->payment_term_id,
            'payment_method_id' => $this->payment_method_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);



        if($this->globalSearch!=''){
            $query->orFilterWhere(['like', 'code', $this->globalSearch])
                ->orFilterWhere(['like', 'name', $this->globalSearch])
                ->orFilterWhere(['like', 'description', $this->globalSearch]);
        }


        return $dataProvider;
    }
}

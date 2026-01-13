<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TaxImport;

/**
 * TaxImportSearch represents the model behind the search form of `backend\models\TaxImport`.
 */
class TaxImportSearch extends TaxImport
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sequence', 'price_type', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['doc_date', 'reference_no', 'vendor_name', 'tax_id', 'branch_code', 'tax_invoice_no', 'tax_invoice_date', 'tax_record_date', 'account_code', 'description', 'tax_rate', 'wht_amount', 'paid_by', 'pnd_type', 'remarks', 'group_type'], 'safe'],
            [['qty', 'unit_price', 'paid_amount'], 'number'],
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
        $query = TaxImport::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
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
            'sequence' => $this->sequence,
            'price_type' => $this->price_type,
            'qty' => $this->qty,
            'unit_price' => $this->unit_price,
            'paid_amount' => $this->paid_amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'doc_date', $this->doc_date])
            ->andFilterWhere(['like', 'reference_no', $this->reference_no])
            ->andFilterWhere(['like', 'vendor_name', $this->vendor_name])
            ->andFilterWhere(['like', 'tax_id', $this->tax_id])
            ->andFilterWhere(['like', 'branch_code', $this->branch_code])
            ->andFilterWhere(['like', 'tax_invoice_no', $this->tax_invoice_no])
            ->andFilterWhere(['like', 'tax_invoice_date', $this->tax_invoice_date])
            ->andFilterWhere(['like', 'tax_record_date', $this->tax_record_date])
            ->andFilterWhere(['like', 'account_code', $this->account_code])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'tax_rate', $this->tax_rate])
            ->andFilterWhere(['like', 'wht_amount', $this->wht_amount])
            ->andFilterWhere(['like', 'paid_by', $this->paid_by])
            ->andFilterWhere(['like', 'pnd_type', $this->pnd_type])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'group_type', $this->group_type]);

        return $dataProvider;
    }
}

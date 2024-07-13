<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transactions;

class TransactionsSearch extends Transactions
{
    public $amount; // Deklarasi properti amount
    public $transaction_date; // Deklarasi properti transaction_date
    public $customer_name; // Deklarasi properti customer_name
    public $month;

    public function rules()
    {
        return [
            [['transaction_id', 'amount'], 'integer'],
            [['transaction_date', 'customer_name', 'month'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Transactions::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'transaction_id' => $this->transaction_id,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name]);

        // Filter by month
        if (!empty($this->month)) {
            $query->andWhere(['MONTH(transaction_date)' => $this->month]);
        }

        return $dataProvider;
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transactionDetails".
 *
 * @property int $detail_id
 * @property int|null $transaction_id
 * @property int|null $product_id
 * @property int $quantity
 * @property float $price
 */
class TransactionDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactionDetails';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_id', 'product_id', 'quantity', 'price'], 'required'],
            [['transaction_id', 'product_id', 'quantity'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'detail_id' => 'Detail ID',
            'transaction_id' => 'Transaction ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'price' => 'Price',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getTransaction()
    {
        return $this->hasOne(Transactions::class, ['transaction_id' => 'transaction_id']);
    }
}

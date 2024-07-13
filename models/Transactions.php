<?php 

namespace app\models;

use Yii;

/**
 * This is the model class for table "transactions".
 *
 * @property int $transaction_id
 * @property int|null $user_id
 * @property float $total
 * @property string $transaction_date
 * @property float|null $amount_paid
 * @property float|null $change_returned
 *
 * @property Transactiondetails[] $transactiondetails
 * @property User $user
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactions';
    }

    public $transactionDetailsData; // properti virtual

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'total', 'transaction_date', 'amount_paid', 'change_returned'], 'required'],
            [['user_id'], 'integer'],
            [['total', 'amount_paid', 'change_returned'], 'number'],
            [['transaction_date'], 'safe'],
            [['transaction_code'], 'string', 'max' => 255],
            [['transactionDetailsData'], 'safe'], //  validasi untuk properti virtual
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transaction_id' => 'Transaction ID',
            'user_id' => 'User ID',
            'total' => 'Total',
            'transaction_date' => 'Transaction Date',
            'transaction_code' => 'Kode Transaksi',
            'amount_paid' => 'Amount Paid',
            'change_returned' => 'Change Returned',
        ];
    }

    /**
     * Gets query for [[Transactiondetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionDetails()
    {
        return $this->hasMany(TransactionDetails::class, ['transaction_id' => 'transaction_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    /**
     * Generates kode_transaksi before saving.
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->transaction_code = $this->generateKodeTransaksi();
            }
            return true;
        }
        return false;
    }

    /**
     * Generate transaction code
     *
     * @return string
     */
    private function generateKodeTransaksi()
    {
        $lastTransaction = self::find()->orderBy(['transaction_id' => SORT_DESC])->one();
        if ($lastTransaction) {
            $lastCode = $lastTransaction->transaction_code;
            $lastNumber = (int)substr($lastCode, 2);
            $newNumber = $lastNumber + 1;
            return 'TR' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        }
        return 'TR00001';
    }
}

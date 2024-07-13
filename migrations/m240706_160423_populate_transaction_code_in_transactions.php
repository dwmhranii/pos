<?php

use yii\db\Migration;

/**
 * Class m240706_160423_populate_transaction_code_in_transactions
 */
class m240706_160423_populate_transaction_code_in_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $transactions = (new \yii\db\Query())
            ->select(['transaction_id'])
            ->from('{{%transactions}}')
            ->all();

        foreach ($transactions as $transaction) {
            $this->update('{{%transactions}}', [
                'transaction_code' => $this->generateUniqueTransactionCode()
            ], ['transaction_id' => $transaction['transaction_id']]);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->update('{{%transactions}}', ['transaction_code' => null]);
    }

    /**
     * Generates a unique transaction code.
     *
     * @return string
     */
    private function generateUniqueTransactionCode()
    {
        static $counter = 0;
        $counter++;
        return 'TR' . str_pad($counter, 5, '0', STR_PAD_LEFT);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240706_160423_populate_transaction_code_in_transactions cannot be reverted.\n";

        return false;
    }
    */
}

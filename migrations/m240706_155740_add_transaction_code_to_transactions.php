<?php

use yii\db\Migration;

/**
 * Class m240706_155740_add_transaction_code_to_transactions
 */
class m240706_155740_add_transaction_code_to_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%transactions}}', 'transaction_code', $this->string()->after('transaction_date'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%transactions}}', 'transaction_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240706_155740_add_transaction_code_to_transactions cannot be reverted.\n";

        return false;
    }
    */
}

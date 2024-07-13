<?php

use yii\db\Migration;

/**
 * Class m240706_160619_add_constraints_to_transaction_code_in_transactions
 */
class m240706_160619_add_constraints_to_transaction_code_in_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%transactions}}', 'transaction_code', $this->string()->notNull()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%transactions}}', 'transaction_code', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240706_160619_add_constraints_to_transaction_code_in_transactions cannot be reverted.\n";

        return false;
    }
    */
}

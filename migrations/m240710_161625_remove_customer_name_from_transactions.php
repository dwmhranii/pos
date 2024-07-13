<?php

use yii\db\Migration;

/**
 * Class m240710_161625_remove_customer_name_from_transactions
 */
class m240710_161625_remove_customer_name_from_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Drop customer_name column from transactions table
        $this->dropColumn('{{%transactions}}', 'customer_name');

        // Remove the index related to customer_name
        $this->dropIndex('idx-transactions-customer_name', '{{%transactions}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Add customer_name column back to transactions table
        $this->addColumn('{{%transactions}}', 'customer_name', $this->string(100)->after('transaction_code'));

        // Add index for customer_name
        $this->createIndex('idx-transactions-customer_name', '{{%transactions}}', 'customer_name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240710_161625_remove_customer_name_from_transactions cannot be reverted.\n";

        return false;
    }
    */
}

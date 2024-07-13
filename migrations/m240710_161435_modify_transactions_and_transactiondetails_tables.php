<?php

use yii\db\Migration;

/**
 * Class m240710_161435_modify_transactions_and_transactiondetails_tables
 */
class m240710_161435_modify_transactions_and_transactiondetails_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Modify transactions table
        $this->addColumn('{{%transactions}}', 'customer_name', $this->string(100)->after('transaction_code'));
        $this->addColumn('{{%transactions}}', 'payment_method', $this->string(50)->after('customer_name'));
        $this->addColumn('{{%transactions}}', 'discount', $this->decimal(10, 2)->defaultValue(0.00)->after('total'));
        $this->addColumn('{{%transactions}}', 'is_void', $this->boolean()->defaultValue(0)->after('discount'));

        // Modify transactiondetails table
        $this->addColumn('{{%transactiondetails}}', 'discount', $this->decimal(10, 2)->defaultValue(0.00)->after('price'));
        $this->addColumn('{{%transactiondetails}}', 'total_price', $this->decimal(10, 2)->after('discount'));
        
        // Update total_price based on quantity and price
        $this->update('{{%transactiondetails}}', ['total_price' => new \yii\db\Expression('quantity * price - discount')]);

        // Add index for the new columns
        $this->createIndex('idx-transactions-customer_name', '{{%transactions}}', 'customer_name');
        $this->createIndex('idx-transactions-payment_method', '{{%transactions}}', 'payment_method');
        $this->createIndex('idx-transactiondetails-discount', '{{%transactiondetails}}', 'discount');
        $this->createIndex('idx-transactiondetails-total_price', '{{%transactiondetails}}', 'total_price');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop columns from transactions table
        $this->dropColumn('{{%transactions}}', 'customer_name');
        $this->dropColumn('{{%transactions}}', 'payment_method');
        $this->dropColumn('{{%transactions}}', 'discount');
        $this->dropColumn('{{%transactions}}', 'is_void');

        // Drop columns from transactiondetails table
        $this->dropColumn('{{%transactiondetails}}', 'discount');
        $this->dropColumn('{{%transactiondetails}}', 'total_price');
        
        // Drop indexes
        $this->dropIndex('idx-transactions-customer_name', '{{%transactions}}');
        $this->dropIndex('idx-transactions-payment_method', '{{%transactions}}');
        $this->dropIndex('idx-transactiondetails-discount', '{{%transactiondetails}}');
        $this->dropIndex('idx-transactiondetails-total_price', '{{%transactiondetails}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240710_161435_modify_transactions_and_transactiondetails_tables cannot be reverted.\n";

        return false;
    }
    */
}

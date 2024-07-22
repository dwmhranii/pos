<?php

use yii\db\Migration;

/**
 * Class m240722_130300_add_customer_name_to_transactions_table
 */
class m240722_130300_add_customer_name_to_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema('{{%transactions}}')->getColumn('customer_name') !== null) {
            $this->dropColumn('{{%transactions}}', 'customer_name');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
        if ($this->db->getTableSchema('{{%transactions}}')->getColumn('customer_name') === null) {
            $this->addColumn('{{%transactions}}', 'customer_name', $this->string(100)->notNull());
        }
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240722_130300_add_customer_name_to_transactions_table cannot be reverted.\n";

        return false;
    }
    */
}

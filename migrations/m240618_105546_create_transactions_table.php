<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transactions}}`.
 */
class m240618_105546_create_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema('{{%transactions}}', true) === null) {
            // Create table if it does not exist
            $this->createTable('{{%transactions}}', [
                'transaction_id' => $this->primaryKey(),
                'user_id' => $this->integer(),
                'total' => $this->decimal(10,2)->notNull(),
                'transaction_date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            ]);

            // Create index for column `user_id`
            $this->createIndex(
                'idx-transactions-user_id',
                '{{%transactions}}',
                'user_id'
            );
        } else {
            // Update table if it exists
            $table = $this->db->getTableSchema('{{%transactions}}');

            // Check and add column `total` if it doesn't exist
            if (!isset($table->columns['total'])) {
                $this->addColumn('{{%transactions}}', 'total', $this->decimal(10,2)->notNull());
            }

            // Check and add column `transaction_date` if it doesn't exist
            if (!isset($table->columns['transaction_date'])) {
                $this->addColumn('{{%transactions}}', 'transaction_date', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
            }

            // Create index for column `user_id` if it doesn't exist
            if (!isset($table->columns['user_id'])) {
                $this->createIndex(
                    'idx-transactions-user_id',
                    '{{%transactions}}',
                    'user_id'
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->db->getTableSchema('{{%transactions}}', true) !== null) {
            // Drop index for column `user_id`
            $this->dropIndex(
                'idx-transactions-user_id',
                '{{%transactions}}'
            );

            // Drop columns if they exist
            $table = $this->db->getTableSchema('{{%transactions}}');
            if (isset($table->columns['total'])) {
                $this->dropColumn('{{%transactions}}', 'total');
            }
            if (isset($table->columns['transaction_date'])) {
                $this->dropColumn('{{%transactions}}', 'transaction_date');
            }

            // Optionally, drop the entire table if needed
            // $this->dropTable('{{%transactions}}');
        }
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transactiondetails}}`.
 */
class m240618_110512_create_transactiondetails_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema('{{%transactiondetails}}', true) === null) {
            // Create table if it does not exist
            $this->createTable('{{%transactiondetails}}', [
                'detail_id' => $this->primaryKey(),
                'transaction_id' => $this->integer(),
                'product_id' => $this->integer(),
                'quantity' => $this->integer()->notNull(),
                'price' => $this->decimal(10,2)->notNull(),
            ]);

            // Create index for column `transaction_id`
            $this->createIndex(
                'idx-transactiondetails-transaction_id',
                '{{%transactiondetails}}',
                'transaction_id'
            );

            // Create index for column `product_id`
            $this->createIndex(
                'idx-transactiondetails-product_id',
                '{{%transactiondetails}}',
                'product_id'
            );

            // Add foreign key for table `transactions`
            $this->addForeignKey(
                'fk-transactiondetails-transaction_id',
                '{{%transactiondetails}}',
                'transaction_id',
                '{{%transactions}}',
                'transaction_id',
                'CASCADE'
            );

            // Add foreign key for table `products` (optional, if you have products table)
            // $this->addForeignKey(
            //     'fk-transactiondetails-product_id',
            //     '{{%transactiondetails}}',
            //     'product_id',
            //     '{{%products}}',
            //     'id',
            //     'CASCADE'
            // );
        } else {
            // Update table if it exists
            $table = $this->db->getTableSchema('{{%transactiondetails}}');

            // Check and add column `transaction_id` if it doesn't exist
            if (!isset($table->columns['transaction_id'])) {
                $this->addColumn('{{%transactiondetails}}', 'transaction_id', $this->integer());
            }

            // Check and add column `product_id` if it doesn't exist
            if (!isset($table->columns['product_id'])) {
                $this->addColumn('{{%transactiondetails}}', 'product_id', $this->integer());
            }

            // Check and add column `quantity` if it doesn't exist
            if (!isset($table->columns['quantity'])) {
                $this->addColumn('{{%transactiondetails}}', 'quantity', $this->integer()->notNull());
            }

            // Check and add column `price` if it doesn't exist
            if (!isset($table->columns['price'])) {
                $this->addColumn('{{%transactiondetails}}', 'price', $this->decimal(10,2)->notNull());
            }

            // Create index for column `transaction_id` if it doesn't exist
            if (!isset($table->columns['transaction_id'])) {
                $this->createIndex(
                    'idx-transactiondetails-transaction_id',
                    '{{%transactiondetails}}',
                    'transaction_id'
                );
            }

            // Create index for column `product_id` if it doesn't exist
            if (!isset($table->columns['product_id'])) {
                $this->createIndex(
                    'idx-transactiondetails-product_id',
                    '{{%transactiondetails}}',
                    'product_id'
                );
            }

            // Add foreign key for table `transactions` if it doesn't exist
            if (!isset($table->foreignKeys['fk-transactiondetails-transaction_id'])) {
                $this->addForeignKey(
                    'fk-transactiondetails-transaction_id',
                    '{{%transactiondetails}}',
                    'transaction_id',
                    '{{%transactions}}',
                    'transaction_id',
                    'CASCADE'
                );
            }

            // Add foreign key for table `products` if it doesn't exist (optional)
            // if (!isset($table->foreignKeys['fk-transactiondetails-product_id'])) {
            //     $this->addForeignKey(
            //         'fk-transactiondetails-product_id',
            //         '{{%transactiondetails}}',
            //         'product_id',
            //         '{{%products}}',
            //         'id',
            //         'CASCADE'
            //     );
            // }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->db->getTableSchema('{{%transactiondetails}}', true) !== null) {
            // Drop the foreign key constraint first if it exists
            $this->dropForeignKey(
                'fk-transactiondetails-transaction_id',
                '{{%transactiondetails}}'
            );

            // Drop index for column `transaction_id`
            $this->dropIndex(
                'idx-transactiondetails-transaction_id',
                '{{%transactiondetails}}'
            );

            // Drop index for column `product_id`
            $this->dropIndex(
                'idx-transactiondetails-product_id',
                '{{%transactiondetails}}'
            );

            // Drop the foreign key constraint for product_id if it exists (optional)
            // $this->dropForeignKey(
            //     'fk-transactiondetails-product_id',
            //     '{{%transactiondetails}}'
            // );

            // Drop columns if they exist
            $table = $this->db->getTableSchema('{{%transactiondetails}}');
            if (isset($table->columns['transaction_id'])) {
                $this->dropColumn('{{%transactiondetails}}', 'transaction_id');
            }
            if (isset($table->columns['product_id'])) {
                $this->dropColumn('{{%transactiondetails}}', 'product_id');
            }
            if (isset($table->columns['quantity'])) {
                $this->dropColumn('{{%transactiondetails}}', 'quantity');
            }
            if (isset($table->columns['price'])) {
                $this->dropColumn('{{%transactiondetails}}', 'price');
            }

            // Optionally, drop the entire table if needed
            // $this->dropTable('{{%transactiondetails}}');
        }
    }
}

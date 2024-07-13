<?php

use yii\db\Migration;

/**
 * Class m240706_073226_add_product_code_to_products
 */
class m240706_073226_add_product_code_to_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'product_code', $this->string()->after('product_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'product_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240706_073226_add_product_code_to_products cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m240615_164204_rename_categories_to_category
 */
class m240615_164204_rename_categories_to_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('categories', 'category');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('category', 'categories');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240615_164204_rename_categories_to_category cannot be reverted.\n";

        return false;
    }
    */
}

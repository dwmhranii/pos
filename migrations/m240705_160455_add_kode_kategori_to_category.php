<?php

use yii\db\Migration;

/**
 * Class m240705_160455_add_kode_kategori_to_category
 */
class m240705_160455_add_kode_kategori_to_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%category}}', 'kode_kategori', $this->string(10)->defaultValue('TEMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%category}}', 'kode_kategori');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240705_160455_add_kode_kategori_to_category cannot be reverted.\n";

        return false;
    }
    */
}

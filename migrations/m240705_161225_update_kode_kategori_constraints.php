<?php

use yii\db\Migration;

/**
 * Class m240705_161225_update_kode_kategori_constraints
 */
class m240705_161225_update_kode_kategori_constraints extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%category}}', 'kode_kategori', $this->string(10)->notNull()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%category}}', 'kode_kategori', $this->string(10)->defaultValue('TEMP'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240705_161225_update_kode_kategori_constraints cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m240614_103421_add_admin_user_to_users_table
 */
class m240614_103421_add_admin_user_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Insert admin user
        $this->insert('{{%users}}', [
            'username' => 'admin',
            'password' => Yii::$app->security->generatePasswordHash('admin'),
            'full_name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => 'superadmin',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => new \yii\db\Expression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Delete admin user
        $this->delete('{{%users}}', ['username' => 'admin']);
    }
}

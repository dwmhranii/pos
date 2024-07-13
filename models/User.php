<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return '{{%users}}';
    }

    public function rules()
    {
        return [
            [['username', 'full_name', 'email', 'role'], 'required'],
            [['username', 'email'], 'unique'],
            [['email'], 'email'],
            [['role'], 'in', 'range' => ['superadmin', 'staff']],
            ['password', 'required', 'on' => 'create'], // Password required only on create
            ['password', 'string', 'min' => 6],
        ];
    }



    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]); // Pastikan token yang digunakan sesuai dengan aplikasi Anda
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->getPrimaryKey(); // Menggunakan metode ini untuk mendapatkan primary key dari model
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password); 
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'Username',
            'password' => 'Password',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'role' => 'Role',
            'auth_key' => 'Auth Key',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Jika password tidak kosong dan berbeda dari password yang di-hash
            if (!empty($this->password) && !$this->isPasswordHash($this->password)) {
                $this->password = Yii::$app->security->generatePasswordHash($this->password);
            }
            return true;
        }
        return false;
    }

    public static function isUserSuperAdmin($id)
    {
        return static::findOne(['user_id' => $id, 'role' => 'superadmin']) !== null;
    }

    private function isPasswordHash($password)
    {
        return preg_match('/^\$2[ayb]\$.{56}$/', $password);
    }
}

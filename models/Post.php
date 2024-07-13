<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%post}}'; // Nama tabel Anda
    }

    public function rules()
    {
        return [
            [['title', 'content', 'category_id'], 'required'],
            [['category_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['content'], 'string'],
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'category_id' => 'Category',
        ];
    }
}

<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bot".
 *
 * @property integer $id
 * @property string $name
 * @property integer $creator_id
 * @property integer $created_at
 * @property string $description
 */
class Bot extends ActiveRecord
{
    public static function tableName()
    {
        return 'bot';
    }

    public function rules()
    {
        return [
            [['name', 'creator_id'], 'required'],
            [['creator_id', 'created_at'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'creator_id' => 'Creator ID',
            'created_at' => 'Created At',
            'description' => 'Description',
        ];
    }

    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'creator_id']);
    }

    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['bot_id' => 'id']);
    }

    public function getMessages()
    {
        return $this->hasMany(Message::class, ['bot_id' => 'id']);
    }
}
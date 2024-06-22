<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property string $text
 * @property integer $bot_id
 */
class Question extends ActiveRecord
{
    public static function tableName()
    {
        return 'question';
    }

    public function rules()
    {
        return [
            [['text', 'bot_id'], 'required'],
            [['bot_id'], 'integer'],
            [['text'], 'string', 'max' => 255],
            [['bot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bot::class, 'targetAttribute' => ['bot_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'bot_id' => 'Bot ID',
        ];
    }

    public function getBot()
    {
        return $this->hasOne(Bot::class, ['id' => 'bot_id']);
    }

    public function getAnswers()
    {
        return $this->hasMany(Answer::class, ['question_id' => 'id']);
    }
}
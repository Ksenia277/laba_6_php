<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property string $text
 * @property integer $user_id
 * @property integer $bot_id
 * @property string $author
 * @property integer $response_id
 * @property string $type
 * @property integer $created_at
 * @property integer $updated_at
 * @property Message $response
 */
class Message extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%message}}';
    }

    public function rules()
    {
        return [
            [['text', 'user_id', 'bot_id'], 'required'],
            [['user_id', 'bot_id', 'response_id'], 'integer'],
            [['text'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['bot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bot::class, 'targetAttribute' => 'id'],
            [['response_id'], 'integer'],
            [['type'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'user_id' => 'User ID',
            'bot_id' => 'Bot ID',
            'author' => 'Author',
            'response_id' => 'Response ID',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getBot()
    {
        return $this->hasOne(Bot::class, ['id' => 'bot_id']);
    }

    public function getAuthor()
    {
        return $this->user->username;
    }

    public function isQuestion()
    {
        return $this->type === 'вопрос';
    }

    public function getResponse()
    {
        return $this->hasOne(self::class, ['id' => 'response_id']);
    }

    public function updateResponse($responseText, $botId)
    {
        $response = $this->response;
        if (!$response) {
            $response = new self();
            $response->user_id = $this->user_id;
            $response->bot_id = $botId;
        }
        $response->text = $responseText;
        $response->save();
        $this->response_id = $response->id;
        $this->save();
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}
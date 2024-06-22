<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bot".
 *
 * @property integer $id
 * @property string $text
 * @property integer $question_id
 */
class Answer extends ActiveRecord
{
    public static function tableName()
    {
        return 'answer';
    }

    public function rules()
    {
        return [
            [['text', 'question_id'], 'required'],
            [['question_id'], 'integer'],
            [['text'], 'string', 'max' => 255],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::class, 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Текст',
            'question_id' => 'ID вопроса',
        ];
    }

    public function getQuestion()
    {
        return $this->hasOne(Question::class, ['id' => 'question_id']);
    }
}
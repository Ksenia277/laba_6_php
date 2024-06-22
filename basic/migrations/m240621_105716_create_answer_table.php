<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%answer}}`.
 */
class m240621_105716_create_answer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%answer}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string(255)->notNull(),
            'question_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-answer-question_id',
            '{{%answer}}',
            'question_id'
        );

        $this->addForeignKey(
            'fk-answer-question_id',
            '{{%answer}}',
            'question_id',
            '{{%question}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-answer-question_id', '{{%answer}}');
        $this->dropIndex('idx-answer-question_id', '{{%answer}}');
        $this->dropTable('{{%answer}}');
    }
}

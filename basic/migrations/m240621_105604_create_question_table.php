<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%question}}`.
 */
class m240621_105604_create_question_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%question}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string(255)->notNull(),
            'bot_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_question_bot_id',
            '{{%question}}',
            'bot_id',
            '{{%bot}}',
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
        $this->dropForeignKey('fk_question_bot_id', '{{%question}}');
        $this->dropTable('{{%question}}');
    }
}

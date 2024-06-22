<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message}}`.
 */
class m240621_143640_create_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string(255)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'bot_id' => $this->integer()->notNull(),
            'author' => $this->string(255),
            'response_id' => $this->integer(),
            'type' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_message_user', '{{%message}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk_message_bot', '{{%message}}', 'bot_id', '{{%bot}}', 'id');
        $this->addForeignKey('fk_message_response', '{{%message}}', 'response_id', '{{%message}}', 'id');  
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_message_response', '{{%message}}'); 
        $this->dropForeignKey('fk_message_user', '{{%message}}');
        $this->dropForeignKey('fk_message_bot', '{{%message}}');
        $this->dropTable('{{%message}}');
    }
}
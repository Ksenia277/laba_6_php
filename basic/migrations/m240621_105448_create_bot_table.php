<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bot}}`.
 */
class m240621_105448_create_bot_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bot}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'creator_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'description' => $this->string(255), 
        ]);

        $this->addForeignKey('fk-bot-creator_id', '{{%bot}}', 'creator_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-bot-creator_id', '{{%bot}}');
        $this->dropTable('{{%bot}}');
    }
}
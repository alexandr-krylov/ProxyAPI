<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%wallet}}`.
 */
class m230816_083730_create_wallet_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%wallet}}', [
            'id' => $this->primaryKey(),
            'owner_id' => $this->string()->notNull(),
            'currency' => $this->string()->notNull(),
            'value' => $this->money()->notNull()->defaultValue(0),
            'type' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('\'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%wallet}}');
    }
}

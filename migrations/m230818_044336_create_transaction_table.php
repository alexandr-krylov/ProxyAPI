<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction}}`.
 */
class m230818_044336_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
            'source' => $this->integer()->notNull(),
            'destination' => $this->integer()->notNull(),
            'currency' => $this->string()->notNull(),
            'value' => $this->money()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('\'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex('idx-transaction-source', 'transaction', 'source');
        $this->addForeignKey('fk-transaction-source', 'transaction', 'source', 'wallet', 'id');
        $this->createIndex('idx-transaction-destination', 'transaction', 'destination');
        $this->addForeignKey('fk-transaction-destination', 'transaction', 'destination', 'wallet', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%transaction}}');
    }
}

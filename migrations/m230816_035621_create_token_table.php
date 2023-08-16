<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%token}}`.
 */
class m230816_035621_create_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%token}}', [
            'id' => $this->primaryKey(),
            'ticker' => $this->string(), 
            'price' => $this->money(),
            'quantity' => $this->integer(),
            'maturity_date' => $this->dateTime(),
            'debitor' => $this->string(),
            'category' => $this->string(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('\'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%token}}');
    }
}

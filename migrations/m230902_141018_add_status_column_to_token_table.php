<?php

use yii\db\Migration;
use app\enums\TickerStatus;

/**
 * Handles adding columns to table `{{%token}}`.
 */
class m230902_141018_add_status_column_to_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%token}}', 'status', $this->integer()->notNull()->defaultValue(TickerStatus::Active->value));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%token}}', 'status');
    }
}

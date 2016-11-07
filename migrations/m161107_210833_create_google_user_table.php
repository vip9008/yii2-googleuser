<?php

use vip9008\googleuser\migrations\Migration;

/**
 * Handles the creation of table `google_user`.
 */
class m161107_210833_create_google_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%google_user}}', [
            'id' => $this->primaryKey(),
            'access_token' => $this->string(),
            'email' => $this->string()->notNull()->unique(),
            'data' => $this->text(),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->truncateTable('{{%google_user}}');
        $this->dropTable('{{%google_user}}');
    }
}

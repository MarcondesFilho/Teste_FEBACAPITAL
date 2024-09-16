<?php

use yii\db\Migration;

/**
 * Handles the creation of table `usuario`.
 */
class m000000_000003_create_usuario_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('usuario', [
            'id' => $this->primaryKey(),
            'login' => $this->string(255)->notNull()->unique(),
            'senha' => $this->string(255)->notNull(),
            'nome' => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('usuario');
    }
}

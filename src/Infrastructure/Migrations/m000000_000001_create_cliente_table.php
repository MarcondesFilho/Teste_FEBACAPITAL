<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cliente`.
 */
class m000000_000001_create_cliente_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('cliente', [
            'id' => $this->primaryKey(),
            'nome' => $this->string(255)->notNull(),
            'cpf' => $this->string(14)->notNull()->unique(),
            'endereco' => $this->text(),
            'sexo' => $this->char(1),
            'imagem' => $this->string()->null(), // Caminho para imagem no S3
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('cliente');
    }
}

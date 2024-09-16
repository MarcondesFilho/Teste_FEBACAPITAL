<?php

use yii\db\Migration;

/**
 * Handles the creation of table `livro`.
 */
class m000000_000002_create_livro_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('livro', [
            'id' => $this->primaryKey(),
            'isbn' => $this->string(13)->notNull()->unique(),
            'titulo' => $this->string(255)->notNull(),
            'autor' => $this->string(255),
            'preco' => $this->decimal(10, 2),
            'estoque' => $this->integer()->defaultValue(0),
            'imagem' => $this->string()->null(), // Caminho para imagem no S3
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('livro');
    }
}

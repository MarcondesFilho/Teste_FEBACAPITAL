<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%livro}}`.
 */
class m240920_121009_create_livro_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%livro}}', [
            'id' => $this->primaryKey(),
            'isbn' => $this->string(13)->notNull()->unique(),
            'titulo' => $this->string(255)->notNull(),
            'autor' => $this->string(255)->notNull(),
            'preco' => $this->decimal(10, 2)->notNull(),
            'estoque' => $this->integer()->notNull(),
            'imagem' => $this->string(255)->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%livro}}');
    }
}

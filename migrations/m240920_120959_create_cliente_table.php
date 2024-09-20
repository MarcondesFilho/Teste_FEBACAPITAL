<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cliente}}`.
 */
class m240920_120959_create_cliente_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%cliente}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string(255)->notNull(),
            'cpf' => $this->string(11)->notNull()->unique(),
            'endereco' => $this->text()->notNull(),
            'sexo' => "ENUM('M', 'F') NOT NULL",
            'imagem' => $this->string(255)->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%cliente}}');
    }
}

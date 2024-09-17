<?php

use yii\db\Migration;
use yii\db\Connection;

/**
 * Handles creating the database if it doesn't exist.
 */
class m000000_000000_create_database extends Migration
{
    public function init()
    {
        // Define a conexão com o MySQL sem especificar o banco de dados.
        $this->db = new Connection([
            'dsn' => 'mysql:host=localhost', // Apenas conexão com o host
            'username' => 'root',            // Altere o nome de usuário, se necessário
            'password' => '',                // Altere a senha, se necessário
            'charset' => 'utf8mb4',
        ]);
    }

    public function safeUp()
    {
        $dbName = 'biblioteca'; // Nome do banco de dados

        // Verifica se o banco de dados já existe
        $dbExists = $this->db->createCommand("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbName")
            ->bindValue(':dbName', $dbName)
            ->queryScalar();

        if (!$dbExists) {
            // Se o banco de dados não existir, cria o banco
            $this->db->createCommand("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
                ->execute();
            echo "Banco de dados `$dbName` criado com sucesso.\n";
        } else {
            echo "Banco de dados `$dbName` já existe.\n";
        }
    }

    public function safeDown()
    {
        $dbName = 'biblioteca';
        // Remove o banco de dados se for necessário reverter
        $this->db->createCommand("DROP DATABASE `$dbName`")->execute();
        echo "Banco de dados `$dbName` removido.\n";
    }
}

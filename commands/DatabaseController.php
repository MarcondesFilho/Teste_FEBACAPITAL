<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Connection;

class DatabaseController extends Controller
{
    public $dbName = 'biblioteca'; 

    /**
     * Comando para criar o banco de dados.
     * 
     * Exemplo de uso:
     * php yii database/create
     */
    public function actionCreate()
    {
        
        if (!$this->databaseExists()) {
            
            $this->createDatabase();
            echo "Banco de dados '{$this->dbName}' criado com sucesso.\n";
        } else {
            echo "Banco de dados '{$this->dbName}' já existe.\n";
        }

        return ExitCode::OK;
    }

    private function databaseExists()
    {
        $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbname";
        return $this->getDbConnection()->createCommand($sql, [':dbname' => $this->dbName])->queryOne() !== false;
    }

    private function createDatabase()
    {
        $sql = "CREATE DATABASE IF NOT EXISTS {$this->dbName} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        $this->getDbConnection()->createCommand($sql)->execute();
    }

    private function getDbConnection()
    {
        return new Connection([
            'dsn' => 'mysql:host=localhost',
            'username' => 'root',             
            'password' => '',                  
        ]);
    }
}

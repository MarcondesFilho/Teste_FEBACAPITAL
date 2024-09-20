<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;
use yii\base\Exception;

class UsuarioController extends Controller
{
    // Comando: php yii usuario/create [login] [senha] [nome]
    public function actionCreate($login, $senha, $nome)
    {
        $usuario = new User();
        $usuario->login = $login;
        $usuario->nome = $nome;
        
        try {
            $usuario->senha = Yii::$app->getSecurity()->generatePasswordHash($senha);
        } catch (Exception $e) {
            echo "Erro ao gerar hash de senha: " . $e->getMessage() . "\n";
            return 1;
        }

        if ($usuario->save()) {
            echo "Usuário {$usuario->login} criado com sucesso!\n";
            return 0;
        } else {
            echo "Erro ao criar usuário: \n";
            foreach ($usuario->errors as $error) {
                echo implode("\n", $error) . "\n";
            }
            return 1;
        }
    }
}

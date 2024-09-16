<?php

namespace src\Application\Commands;

use Yii;
use yii\console\Controller;
use src\Domain\Entities\Usuario;
use src\Domain\Repositories\UsuarioRepository;
use src\Application\Services\AuthService;

class UsuarioController extends Controller
{
    private $usuarioRepository;

    public function __construct($id, $module, UsuarioRepository $usuarioRepository, AuthService $authService, $config = [])
    {
        $this->authService = $authService;
        $this->usuarioRepository = $usuarioRepository;
        parent::__construct($id, $module, $config);
    }

    public function actionCreate($login, $senha, $nome)
    {
        // Aqui passamos os argumentos corretos para o construtor de Usuario
        $usuario = new Usuario($login, Yii::$app->getSecurity()->generatePasswordHash($senha), $nome);

        if ($this->usuarioRepository->save($usuario)) {
            echo "Usuário {$login} criado com sucesso.\n";
            return 0;  // Código de sucesso
        } else {
            echo "Erro ao criar o usuário.\n";
            return 1;  // Código de erro
        }
    }

    public function actionLogin($login, $senha)
    {
        $token = $this->authService->authenticate($login, $senha);

        if ($token) {
            echo "Login realizado com sucesso. Token: $token\n";
        } else {
            echo "Falha no login. Credenciais incorretas.\n";
        }
    }
}

    

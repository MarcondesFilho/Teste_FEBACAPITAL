<?php

namespace src\Application\Commands;

use Yii;
use yii\rest\ActiveController;
use src\Domain\Entities\Usuario;
use src\Domain\Repositories\UsuarioRepository;
use yii\filters\auth\HttpBearerAuth;
use src\Infrastructure\JWT\JWTService;

class UsuarioController extends ActiveController
{
    private $usuarioRepository;

    public function __construct($id, $module, UsuarioRepository $usuarioRepository, $config = [])
    {
        $this->usuarioRepository = $usuarioRepository;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // Adicionando autenticação via JWT
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'auth' => [JWTService::class, 'authenticate'], // Use o serviço JWT que você criou para autenticação
        ];

        return $behaviors;
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

}

    

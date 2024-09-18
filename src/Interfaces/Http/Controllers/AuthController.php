<?php

namespace src\Interfaces\Http\Controllers;

use src\Application\Services\AuthService;
use Yii;
use yii\rest\ActiveController;
use src\Infrastructure\JWT\JWTService;
use src\Domain\Entities\Usuario;
use yii\web\Response;

class AuthController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::class,
            'actions' => [
                'login' => ['post'],
                'register' => ['post'],
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => JWTService::class,
            'except' => ['login'],
        ];
        return $behaviors;
    }

    private AuthService $authService;

    public function __construct($id, $module, AuthService $authService, $config = [])
    {
        $this->authService = $authService;
        parent::__construct($id, $module, $config);
    }

    public function actionRegister($login, $senha, $nome)
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

    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request->post();
        $login = $request['login'] ?? '';
        $senha = $request['senha'] ?? '';
        
        $token = $this->authService->authenticate($login, $senha);
        if (!$token) {
            return ['error' => 'Credenciais inválidas'];
        }

        return ['token' => $token];
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request->post();
        $usuario = $this->usuarioRepository->findById($id);

        if (!$usuario) {
            throw new \Exception('Usuário não encontrado.');
        }

        $usuario->setNome($request['nome']);
        $usuario->setSenha($request['senha']);

        if ($this->usuarioRepository->save($usuario)) {
            return ['status' => 'success', 'message' => 'Usuário atualizado com sucesso.'];
        }

        return ['status' => 'error', 'message' => 'Erro ao atualizar usuário.'];
    }
}

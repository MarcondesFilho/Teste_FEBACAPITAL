<?php

namespace src\Interfaces\Http\Controllers;

use src\Application\Services\AuthService;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use src\Infrastructure\JWT\JWTService;
use yii\web\Response;

class AuthController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // Desabilita a autenticação para a rota de login
        $behaviors['authenticator']['except'] = ['login'];

        // Opcional: Configuração de controle de acesso
        $behaviors['access'] = [
            'class' => \yii\filters\AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['login'],
                    'roles' => ['?'], // '?' significa visitantes (usuários não autenticados)
                ],
            ],
        ];
        
        return $behaviors;
    }

    private AuthService $authService;

    public function __construct($id, $module, AuthService $authService, $config = [])
    {
        $this->authService = $authService;
        parent::__construct($id, $module, $config);
    }

    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request->post();
        $login = $request['login'] ?? null;
        $senha = $request['senha'] ?? null;

        $token = $this->authService->authenticate($login, $senha);

        if ($token) {
            return ['status' => 'success', 'token' => $token];
        }

        return $this->asJson(['error' => 'Credenciais inválidas'], 401);
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

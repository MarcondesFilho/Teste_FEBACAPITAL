<?php

namespace src\Interfaces\Http\Controllers;

use src\Application\Services\AuthService;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use src\Infrastructure\JWT\JWTService;

class AuthController extends \yii\rest\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // Adicionando autenticação via JWT
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'auth' => [JWTService::class, 'authenticate'], // Use o serviço JWT que você criou para autenticação
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
        $request = Yii::$app->request->post();

        $token = $this->authService->authenticate($request['username'], $request['password']);

        if ($token) {
            return $this->asJson(['token' => $token]);
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

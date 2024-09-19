<?php

namespace app\controllers;

use app\services\AuthService;
use Yii;
use yii\rest\Controller;

class AuthController extends Controller
{
    private $authService;

    public function __construct($id, $module, AuthService $authService, $config = [])
    {
        $this->authService = $authService;
        parent::__construct($id, $module, $config);
    }

    public function actionLogin()
    {
        $login = Yii::$app->request->post('login');
        $senha = Yii::$app->request->post('senha');

        try {
            $token = $this->authService->authenticate($login, $senha);
            return ['token' => $token];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 401;
            return ['error' => $e->getMessage()];
        }
    }

    public function actionRegister()
    {
        $login = Yii::$app->request->post('login');
        $senha = Yii::$app->request->post('senha');
        $nome = Yii::$app->request->post('nome');

        try {
            $usuario = $this->authService->register($login, $senha, $nome);
            return ['message' => 'Usuário criado com sucesso', 'usuario' => $usuario];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 400;
            return ['error' => $e->getMessage()];
        }
    }
}

<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\rest\Controller;
use app\models\Cliente;
use app\services\ClienteService;
use yii\web\HttpException;
use app\services\JWTService;
use app\services\ImageUploadService;

class ClienteController extends Controller
{
    private $clienteService;
    private $jwtService;

    public function __construct($id, $module, ClienteService $clienteService, JWTService $jwtService, ImageUploadService $imageUploadService, $config = [])
    {
        $this->imageUploadService = $imageUploadService;
        $this->clienteService = $clienteService;
        $this->jwtService = $jwtService;
        parent::__construct($id, $module, $config);
    }

    private function validateToken()
    {
        $token = $this->jwtService->getTokenFromHeader();
        $this->jwtService->validateToken($token);
    }

    public function actionCreate()
    {
        $this->validateToken();

        $request = Yii::$app->request->post();
        
        $cliente = new Cliente();
        $cliente->attributes = $request;

        $cliente->formatEndereco(
            $request['cep'],
            $request['logradouro'],
            $request['numero'],
            $request['cidade'],
            $request['estado'],
            $request['complemento']
        );
        
        $imageFile = UploadedFile::getInstanceByName('imagem');
        if ($imageFile) {
            $cliente->imagem = $this->imageUploadService->uploadImage($imageFile, 'clientes');
        }

        if ($cliente->validate() && $cliente->save()) {
            return $this->asJson(['message' => 'Cliente cadastrado com sucesso'])
                ->setStatusCode(201);
        }

        return $this->asJson($cliente->errors)
            ->setStatusCode(400);
    }

    public function actionIndex()
    {
        $this->validateToken();

        $params = Yii::$app->request->queryParams;

        try {
            $clientes = $this->clienteService->listarClientes($params);
            return $this->asJson(['data' => $clientes])
            ->setStatusCode(200);
        } catch (\Exception $e) {
            return $this->asJson(['error' => $e->getMessage()])
            ->setStatusCode(400);
        }
    }

    public function actionView($login)
    {
        $this->validateToken();

        $cliente = Cliente::findOne($login);
        if ($cliente) {
            return $this->asJson($cliente)
                ->setStatusCode(200);
        }

        throw new HttpException(404, 'Cliente não encontrado');
    }

    public function actionUpdate($id)
    {
        $this->validateToken();

        $cliente = Cliente::findOne($id);
        if (!$cliente) {
            throw new HttpException(404, 'Cliente não encontrado');
        }

        $request = Yii::$app->request->post();
        $cliente->attributes = $request;

        if ($cliente->validate() && $cliente->save()) {
            return $this->asJson(['message' => 'Cliente atualizado com sucesso'])
                ->setStatusCode(200);
        }

        return $this->asJson($cliente->errors)
            ->setStatusCode(400);
    }

    public function actionDelete($id)
    {
        $this->validateToken();

        $cliente = Cliente::findOne($id);
        if ($cliente && $cliente->delete()) {
            return $this->asJson(['message' => 'Cliente deletado com sucesso'])
                ->setStatusCode(204);
        }

        throw new HttpException(404, 'Cliente não encontrado');
    }
}

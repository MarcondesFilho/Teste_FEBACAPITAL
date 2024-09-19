<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\rest\Controller;
use app\models\Cliente;
use app\services\ClienteService;
use yii\web\HttpException;
use yii\filters\auth\HttpBearerAuth;

class ClienteController extends Controller
{
    private $clienteService;
    private $imageUploadService;

    public function __construct($id, $module, ClienteService $clienteService, $config = [])
    {
        $this->clienteService = $clienteService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actionCreate()
    {
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
        if ($imageFile && $imageFile->size <= 2097152) { // Max 2MB
            $cliente->imagem = $this->clienteService->uploadImage($imageFile);
        } else {
            return $this->asJson(['error' => 'Invalid image or exceeded size limit.'])
                ->setStatusCode(400);
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
        $cliente = Cliente::findOne($login);
        if ($cliente) {
            return $this->asJson($cliente)
                ->setStatusCode(200);
        }

        throw new HttpException(404, 'Cliente não encontrado');
    }

    public function actionUpdate($id)
    {
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
        $cliente = Cliente::findOne($id);
        if ($cliente && $cliente->delete()) {
            return $this->asJson(['message' => 'Cliente deletado com sucesso'])
                ->setStatusCode(204);
        }

        throw new HttpException(404, 'Cliente não encontrado');
    }
}

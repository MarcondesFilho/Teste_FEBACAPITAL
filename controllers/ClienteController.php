<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\rest\Controller;
use app\models\Cliente;
use app\services\ClienteService;
use yii\web\BadRequestHttpException;

class ClienteController extends Controller
{
    private $clienteService;
    private $imageUploadService;

    public function __construct($id, $module, ClienteService $clienteService, $config = [])
    {
        $this->clienteService = $clienteService;
        parent::__construct($id, $module, $config);
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
            throw new BadRequestHttpException('Imagem inválida ou tamanho excedido.');
        }
        
        if ($cliente->validateCep($cliente->cep) && $cliente->validate() && $cliente->save()) {
            return ['message' => 'Cliente cadastrado com sucesso', 'image' => $cliente->imagem];
        }

        throw new BadRequestHttpException('Erro ao cadastrar o cliente');
    }

    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;

        try {
            $clientes = $this->clienteService->listarClientes($params);
            return ['data' => $clientes];
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}

<?php

namespace src\Interfaces\Http\Controllers;

use src\Application\Services\ClienteService;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

class ClienteController extends Controller
{
    private ClienteService $clienteService;

    public function __construct($id, $module, ClienteService $clienteService, $config = [])
    {
        $this->clienteService = $clienteService;
        parent::__construct($id, $module, $config);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request->post();
        $imagem = UploadedFile::getInstanceByName('imagem');

        if ($this->clienteService->cadastrarCliente($request, $imagem)) {
            return $this->asJson(['status' => 'success']);
        }

        return $this->asJson(['error' => 'Erro ao cadastrar cliente'], 400);
    }

    public function actionList()
    {
        $request = Yii::$app->request->get();
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;
        $orderBy = $request['orderBy'] ?? 'nome';
        $filterBy = $request['filterBy'] ?? null;

        $clientes = $this->clienteService->listarClientes($limit, $offset, $orderBy, $filterBy);
        return $this->asJson(['clientes' => $clientes]);
    }
}

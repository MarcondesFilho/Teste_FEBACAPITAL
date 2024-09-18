<?php

namespace src\Interfaces\Http\Controllers;

use src\Application\Services\ClienteService;
use src\Interfaces\Http\Requests\ClienteRequest;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;

class ClienteController extends Controller
{
    private ClienteService $clienteService;

    public function __construct($id, $module, ClienteService $clienteService, $config = [])
    {
        $this->clienteService = $clienteService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => \sizeg\jwt\JwtHttpBearerAuth::class,
        ];
        return $behaviors;
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

    public function actionUpdate($id)
    {
        $clienteRequest = new ClienteRequest();

        if ($clienteRequest->load(Yii::$app->request->post(), '') && $clienteRequest->validate()) {
            $imagem = UploadedFile::getInstanceByName('imagem'); // Obtém a imagem enviada, se houver
            try {
                $this->clienteService->update($id, $clienteRequest->getAttributes(), $imagem); // Atualiza passando a imagem
                return ['status' => 'success', 'message' => 'Cliente atualizado com sucesso.'];
            } catch (\Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        throw new NotFoundHttpException('Cliente não encontrado ou dados inválidos.');
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

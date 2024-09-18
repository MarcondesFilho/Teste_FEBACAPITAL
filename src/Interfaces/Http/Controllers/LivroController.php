<?php

namespace src\Interfaces\Http\Controllers;

use src\Application\Services\LivroService;
use src\Interfaces\Http\Requests\LivroRequest;
use yii\web\NotFoundHttpException;
use Yii;
use yii\rest\ActiveController;
use yii\web\UploadedFile;
use yii\filters\auth\HttpBearerAuth;
use src\Infrastructure\JWT\JWTService;
use src\Infrastructure\ActiveRecord\LivroAR;
use yii\data\ActiveDataProvider;

class LivroController extends ActiveController
{
    private LivroService $livroService;

    public function __construct($id, $module, LivroService $livroService, $config = [])
    {
        $this->livroService = $livroService;
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

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => LivroAR::find(),
            'pagination' => ['pageSize' => 20],
        ]);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request->post();
        $imagem = UploadedFile::getInstanceByName('imagem');

        if ($this->livroService->cadastrarLivro($request)) {
            return $this->asJson(['status' => 'success']);
        }

        return $this->asJson(['error' => 'Erro ao cadastrar livro'], 400);
    }

    public function actionUpdate($id)
    {
        $livroRequest = new LivroRequest();

        if ($livroRequest->load(Yii::$app->request->post(), '') && $livroRequest->validate()) {
            $imagem = UploadedFile::getInstanceByName('imagem'); // Obtém a imagem enviada, se houver
            try {
                $this->livroService->update($id, $livroRequest->getAttributes(), $imagem); // Atualiza passando a imagem
                return ['status' => 'success', 'message' => 'Livro atualizado com sucesso.'];
            } catch (\Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        throw new NotFoundHttpException('Livro não encontrado ou dados inválidos.');
    }

    public function actionList()
    {
        $request = Yii::$app->request->get();
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;
        $orderBy = $request['orderBy'] ?? 'titulo';
        $filterBy = $request['filterBy'] ?? null;

        $livros = $this->livroService->listarLivros($limit, $offset, $orderBy, $filterBy);
        return $this->asJson(['livros' => $livros]);
    }

    public function actionDelete($id)
    {
        $cliente = LivroAR::findOne($id);
        if (!$cliente) {
            throw new \yii\web\NotFoundHttpException("Cliente não encontrado");
        }
        $cliente->delete();
        return ['status' => 'Cliente deletado'];
    }
}

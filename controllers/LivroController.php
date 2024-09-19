<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\rest\Controller;
use app\models\Livro;
use app\services\LivroService;
use yii\web\HttpException;
use yii\filters\auth\HttpBearerAuth;

class LivroController extends Controller
{
    private $livroService;

    public function __construct($id, $module, LivroService $livroService, $config = [])
    {
        $this->livroService = $livroService;
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
        
        $livro = new Livro();
        $livro->attributes = $request;
        
        $imageFile = UploadedFile::getInstanceByName('imagem');
        if ($imageFile && $imageFile->size <= 2097152) { // Max 2MB
            $livro->imagem = $this->livroService->uploadImage($imageFile);
        } else {
            return $this->asJson(['error' => 'Invalid image or exceeded size limit.'])
                ->setStatusCode(400);
        }

        if ($livro->validate() && $livro->save()) {
            return $this->asJson(['message' => 'Livro cadastrado com sucesso'])
                ->setStatusCode(201);
        }

        return $this->asJson($livro->errors)
            ->setStatusCode(400);
    }

    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        try{
            $query = Livro::find();
            if (isset($params['titulo'])) {
                $query->andFilterWhere(['like', 'titulo', $params['titulo']]);
            }
            if (isset($params['autor'])) {
                $query->andFilterWhere(['like', 'autor', $params['autor']]);
            }
            if (isset($params['isbn'])) {
                $query->andFilterWhere(['like', 'isbn', $params['isbn']]);
            }

            $livros = $query->orderBy(['titulo' => SORT_ASC])
                            ->limit($params['limit'] ?? 10)
                            ->offset($params['offset'] ?? 0)
                            ->all();

            return $this->asJson(['data' => $livros])
            ->setStatusCode(200);

        } catch (\Exception $e) {
            return $this->asJson(['error' => $e->getMessage()])
                ->setStatusCode(400);
        }
    }

    public function actionView($id)
    {
        $livro = Livro::findOne($id);
        if ($livro) {
            return $this->asJson($livro)
                ->setStatusCode(200);
        }

        throw new HttpException(404, 'Livro não encontrado');
    }

    public function actionUpdate($id)
    {
        $livro = Livro::findOne($id);
        if (!$livro) {
            throw new HttpException(404, 'Livro não encontrado');
        }

        $request = Yii::$app->request->post();
        $livro->attributes = $request;

        if ($livro->validate() && $livro->save()) {
            return $this->asJson(['message' => 'Livro atualizado com sucesso'])
                ->setStatusCode(200);
        }

        return $this->asJson($livro->errors)
            ->setStatusCode(400);
    }

    public function actionDelete($id)
    {
        $livro = Livro::findOne($id);
        if ($livro && $livro->delete()) {
            return $this->asJson(['message' => 'Livro deletado com sucesso'])
                ->setStatusCode(204);
        }

        throw new HttpException(404, 'Livro não encontrado');
    }
}

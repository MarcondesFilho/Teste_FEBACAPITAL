<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\Livro;
use app\services\LivroService;
use yii\web\BadRequestHttpException;

class LivroController extends Controller
{
    private $livroService;

    public function __construct($id, $module, LivroService $livroService, $config = [])
    {
        $this->livroService = $livroService;
        parent::__construct($id, $module, $config);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request->post();
        
        $livro = new Livro();
        $livro->attributes = $request;
        
        // Validate and upload the image
        $imageFile = UploadedFile::getInstanceByName('imagem');
        if ($imageFile && $imageFile->size <= 2097152) { // Max 2MB
            $livro->imagem = $this->livroService->uploadImage($imageFile);
        } else {
            throw new BadRequestHttpException('Invalid image or exceeds size limit');
        }
        
        if ($livro->validate() && $livro->save()) {
            return ['message' => 'Livro cadastrado com sucesso', 'image' => $livro->imagem];
        }

        throw new BadRequestHttpException('Erro ao cadastrar o livro');
    }

    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;

        try {
            $livros = $this->livroService->listarLivros($params);
            return ['data' => $livros];
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}

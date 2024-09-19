<?php

namespace app\services;

use app\models\Livro;
use Yii;

class LivroService
{
    public function validarIsbn($isbn)
    {
        $client = new Livro();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl("https://brasilapi.com.br/api/isbn/v1/{$isbn}")
            ->send();

        if ($response->isOk) {
            return $response->data;
        } else {
            return false;
        }
    }

    public function cadastrarLivro($data)
    {
        $livro = new \app\models\Livro();
        $livro->attributes = $data;

        // Validar ISBN via BrasilAPI
        if (!$this->validarIsbn($livro->isbn)) {
            throw new \yii\web\BadRequestHttpException('ISBN inválido.');
        }

        if ($livro->validate() && $livro->save()) {
            return $livro;
        }

        throw new \yii\web\BadRequestHttpException('Erro ao salvar livro.');
    }

    public function listarLivros($params)
    {
        $query = Livro::find();

        // Filtros
        if (!empty($params['titulo'])) {
            $query->andFilterWhere(['like', 'titulo', $params['titulo']]);
        }

        if (!empty($params['autor'])) {
            $query->andFilterWhere(['like', 'autor', $params['autor']]);
        }

        if (!empty($params['isbn'])) {
            $query->andFilterWhere(['isbn' => $params['isbn']]);
        }

        // Ordenação
        $sort = $params['sort'] ?? 'titulo';
        $order = $params['order'] ?? SORT_ASC;
        $query->orderBy([$sort => $order]);

        // Paginação
        $limit = isset($params['limit']) ? (int) $params['limit'] : 10;
        $offset = isset($params['offset']) ? (int) $params['offset'] : 0;
        $query->limit($limit)->offset($offset);

        return $query->all();
    }

    public function uploadImage($file)
    {
        $filesystem = Yii::$app->s3;
        
        $filename = 'uploads/livros/' . uniqid() . '.' . $file->extension;
        $stream = fopen($file->tempName, 'r+');
        
        if ($filesystem->writeStream($filename, $stream)) {
            fclose($stream);
            return $filename;
        }
        
        fclose($stream);
        throw new \Exception('Erro ao fazer upload da imagem');
    }
}

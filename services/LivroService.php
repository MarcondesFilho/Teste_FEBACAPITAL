<?php

namespace app\services;

use app\models\Livro;
use Yii;

class LivroService
{
    public function cadastrarLivro($data)
    {
        $livro = new Livro();
        $livro->setAttributes($data);

        if (!$livro->validate()) {
            return ['errors' => $livro->errors];
        }

        if ($livro->save()) {
            return $livro;
        }

        return ['errors' => 'Erro ao salvar o livro.'];
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

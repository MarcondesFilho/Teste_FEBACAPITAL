<?php

namespace app\services;

use Yii;
use app\models\Cliente;

class ClienteService
{
    public function validarCep($cep)
    {
        $client = new Cliente();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl("https://brasilapi.com.br/api/cep/v1/{$cep}")
            ->send();

        if ($response->isOk) {
            return $response->data; // Retorna os dados do CEP, se for válido
        } else {
            throw new \Exception('CEP inválido');
        }
    }

    public function listarClientes($params)
    {
        $query = Cliente::find();

        // Filtros
        if (!empty($params['nome'])) {
            $query->andFilterWhere(['like', 'nome', $params['nome']]);
        }

        if (!empty($params['cpf'])) {
            $query->andFilterWhere(['like', 'cpf', $params['cpf']]);
        }

        // Ordenação
        $sort = $params['sort'] ?? 'nome';
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
        
        $filename = 'uploads/' . uniqid() . '.' . $file->extension;
        $stream = fopen($file->tempName, 'r+');
        
        if ($filesystem->writeStream($filename, $stream)) {
            fclose($stream);
            return $filename;
        }
        
        fclose($stream);
        throw new \Exception('Erro ao fazer upload da imagem');
    }
}
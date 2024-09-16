<?php

namespace src\Infrastructure\Services;

use Yii;

class BrasilApiService
{
    public static function validateCep(string $cep): bool
    {
        $url = "https://brasilapi.com.br/api/cep/v1/{$cep}";
        $response = Yii::$app->httpClient->get($url)->send();

        return $response->isOk;
    }

    public static function buscarDadosIsbn(string $isbn): ?array
    {
        $url = "https://brasilapi.com.br/api/isbn/v1/{$isbn}";
        $response = Yii::$app->httpClient->get($url)->send();

        if ($response->isOk) {
            return $response->data;
        }

        return null;
    }
}

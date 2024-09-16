<?php

namespace src\Interfaces\Http\Requests;

use yii\base\Model;

class ClienteListRequest extends Model
{
    public $limit;
    public $offset;
    public $orderBy;
    public $filterBy;

    public function rules()
    {
        return [
            [['limit', 'offset'], 'integer'],
            ['orderBy', 'in', 'range' => ['nome', 'cpf', 'cidade']],
            ['filterBy', 'string'],
        ];
    }
}

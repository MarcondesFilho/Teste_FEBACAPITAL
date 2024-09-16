<?php

namespace src\Interfaces\Http\Requests;

use yii\base\Model;

class ClienteRequest extends Model
{
    public $nome;
    public $cpf;
    public $cep;
    public $logradouro;
    public $numero;
    public $cidade;
    public $estado;
    public $complemento;
    public $sexo;

    public function rules()
    {
        return [
            [['nome', 'cpf', 'cep', 'logradouro', 'numero', 'cidade', 'estado', 'sexo'], 'required'],
            ['cpf', 'match', 'pattern' => '/^\d{3}\.\d{3}\.\d{3}-\d{2}$/'],
            ['cep', 'match', 'pattern' => '/^\d{5}-\d{3}$/'],
            ['sexo', 'in', 'range' => ['M', 'F']],
        ];
    }
}

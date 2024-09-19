<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Cliente extends ActiveRecord
{
    public $imagemFile;

    public static function tableName()
    {
        return 'cliente'; // Nome da tabela no banco de dados
    }

    public function rules()
    {
        return [
            [['nome', 'cpf', 'cep', 'logradouro', 'numero', 'cidade', 'estado', 'sexo'], 'required'],
            [['cpf'], 'match', 'pattern' => '/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/'], // Validação de CPF
            [['cep'], 'string', 'length' => [8, 8]], // Validação de CEP
            [['sexo'], 'in', 'range' => ['M', 'F']], // Validação de sexo
            [['numero'], 'integer'],
            [['complemento'], 'string'],
            [['imagemFile'], 'file', 'extensions' => 'jpg, png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => 'Tamanho máximo permitido é 2MB'],
        ];
    }
}

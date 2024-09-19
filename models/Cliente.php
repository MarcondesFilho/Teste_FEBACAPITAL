<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Cliente extends ActiveRecord
{
    public $imagemFile;

    public static function tableName()
    {
        return 'cliente';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['nome', 'cpf', 'endereco', 'sexo'], 'required'],
            [['cpf'], 'match', 'pattern' => '/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/'],
            [['sexo'], 'in', 'range' => ['M', 'F']],
            [['endereco', 'imagem'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'cpf' => 'CPF',
            'endereco' => 'Endereço Completo',
            'sexo' => 'Sexo',
            'imagem' => 'Imagem',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    public function formatEndereco($cep, $logradouro, $numero, $cidade, $estado, $complemento)
    {
        $this->endereco = "CEP: {$cep}, {$logradouro}, {$numero}, {$complemento}, {$cidade} - {$estado}";
    }
}

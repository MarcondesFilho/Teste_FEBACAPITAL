<?php

namespace src\Infrastructure\ActiveRecord;

use yii\db\ActiveRecord;

class ClienteAR extends ActiveRecord
{
    public static function tableName()
    {
        return 'cliente';
    }

    public function rules()
    {
        return [
            [['nome', 'cpf'], 'required'],
            ['cpf', 'string', 'max' => 14],
            ['cpf', 'unique'],
            ['sexo', 'in', 'range' => ['M', 'F']],
            ['imagem', 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }
}

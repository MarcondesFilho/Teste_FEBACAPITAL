<?php

namespace src\Infrastructure\ActiveRecord;

use yii\db\ActiveRecord;

class UsuarioAR extends ActiveRecord
{
    public static function tableName()
    {
        return 'usuario';
    }

    public function rules()
    {
        return [
            [['login', 'senha', 'nome'], 'required'],
            ['login', 'string', 'max' => 255], 
            ['login', 'unique'], 
            ['senha', 'string', 'min' => 6], 
            ['nome', 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

}

<?php

namespace src\Interfaces\Http\Requests;

use yii\base\Model;

class LoginRequest extends Model
{
    public $login;
    public $senha;

    public function rules()
    {
        return [
            [['login', 'senha'], 'required'],
            ['login', 'string'],
            ['senha', 'string'],
        ];
    }
}

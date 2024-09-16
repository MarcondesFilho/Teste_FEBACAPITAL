<?php

namespace src\Interfaces\Http\Requests;

use yii\base\Model;

class LoginRequest extends Model
{
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'string'],
            ['password', 'string'],
        ];
    }
}

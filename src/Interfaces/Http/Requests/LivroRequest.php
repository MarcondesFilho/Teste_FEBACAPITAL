<?php

namespace src\Interfaces\Http\Requests;

use yii\base\Model;

class LivroRequest extends Model
{
    public $isbn;
    public $titulo;
    public $autor;
    public $preco;
    public $estoque;

    public function rules()
    {
        return [
            [['isbn', 'titulo', 'autor', 'preco', 'estoque'], 'required'],
            ['isbn', 'string', 'min' => 10, 'max' => 13],
            ['titulo', 'string'],
            ['autor', 'string'],
            ['preco', 'number', 'min' => 0],
            ['estoque', 'integer', 'min' => 0],
        ];
    }
}

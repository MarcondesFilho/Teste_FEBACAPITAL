<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Livro extends ActiveRecord
{
    public $imagemFile;

    public static function tableName()
    {
        return 'livro'; 
    }

    public function rules()
    {
        return [
            [['isbn', 'titulo', 'autor', 'preco', 'estoque'], 'required'],
            ['isbn', 'match', 'pattern' => '/^\d{3}-\d{10}$/', 'message' => 'Formato de ISBN inválido'],
            ['preco', 'number', 'min' => 0, 'message' => 'Preço deve ser positivo'],
            ['estoque', 'integer', 'min' => 0, 'message' => 'Estoque deve ser um número inteiro'],
            [['imagemFile'], 'file', 'extensions' => 'jpg, png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => 'Tamanho máximo permitido é 2MB'],
        ];
    }
}

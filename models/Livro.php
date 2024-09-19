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
            ['isbn', 'match', 'pattern' => '/^\d{3}-\d{1,5}-\d{1,7}-\d{1,7}-\d{1,7}$/'], // Exemplo de regex para ISBN
            [['preco'], 'number'],
            [['estoque'], 'integer'],
            [['imagemFile'], 'file', 'extensions' => 'jpg, png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => 'Tamanho máximo permitido é 2MB'],
        ];
    }
}

<?php

namespace app\models;

use yii\db\ActiveRecord;

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
            [['preco'], 'number'],
            [['estoque'], 'integer'],
            [['imagem'], 'string'],
            [['isbn'], 'string', 'max' => 13],
            [['titulo', 'autor'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'isbn' => 'ISBN',
            'titulo' => 'Título',
            'autor' => 'Autor',
            'preco' => 'Preço',
            'estoque' => 'Estoque',
            'imagem' => 'Imagem',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }
}

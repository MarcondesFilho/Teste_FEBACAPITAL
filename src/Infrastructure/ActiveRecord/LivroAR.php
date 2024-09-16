<?php

namespace src\Infrastructure\ActiveRecord;

use yii\db\ActiveRecord;

class LivroAR extends ActiveRecord
{
    public static function tableName()
    {
        return 'livro';
    }

    public function rules()
    {
        return [
            [['isbn', 'titulo'], 'required'],
            ['isbn', 'string', 'max' => 13],
            ['isbn', 'unique'],
            ['preco', 'number'],
            ['estoque', 'integer'],
            ['imagem', 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }
}

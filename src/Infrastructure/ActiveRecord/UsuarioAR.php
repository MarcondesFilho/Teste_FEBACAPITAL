<?php

namespace src\Infrastructure\ActiveRecord;

use yii\db\ActiveRecord;

class UsuarioAR extends ActiveRecord
{
    public static function tableName()
    {
        return 'livro';
    }

}

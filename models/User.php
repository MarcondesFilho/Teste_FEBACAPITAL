<?php

namespace app\models;

use yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['login', 'senha', 'nome'], 'required'],
            ['login', 'unique', 'message' => 'Esse login já está em uso.'],
            ['login', 'string', 'max' => 255],
            ['senha', 'string', 'min' => 8],
            ['nome', 'string', 'max' => 255],
            ['auth_key', 'string', 'max' => 255],
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->auth_key = Yii::$app->security->generateRandomString();
        }

        return parent::beforeSave($insert);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    public function getId()
    {
        return $this->$this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

}

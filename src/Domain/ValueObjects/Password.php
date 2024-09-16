<?php

namespace src\Domain\ValueObjects;

use Yii;

class Password
{
    private string $hashedPassword;

    public function __construct(string $plainPassword)
    {
        $this->hashedPassword = Yii::$app->getSecurity()->generatePasswordHash($plainPassword);
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    public function validate(string $plainPassword): bool
    {
        return Yii::$app->getSecurity()->validatePassword($plainPassword, $this->hashedPassword);
    }
}

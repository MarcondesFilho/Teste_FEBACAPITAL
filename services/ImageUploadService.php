<?php

namespace app\services;

use Yii;

class ImageUploadService
{
    public function upload($file)
    {
        if (!$file->validate()) {
            return false;
        }

        $filePath = 'uploads/' . uniqid() . '.' . $file->extension;

        $stream = fopen($file->tempName, 'r+');
        Yii::$app->s3->writeStream($filePath, $stream);
        fclose($stream);

        return $filePath;
    }
}

<?php

namespace app\services;

use Yii;
use yii\web\UploadedFile;
use League\Flysystem\Filesystem;

class ImageUploadService
{
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function uploadImage(UploadedFile $imageFile)
    {
        if (!in_array($imageFile->extension, ['jpg', 'png'])) {
            return ['error' => 'Formato de imagem não permitido. Apenas JPG e PNG são aceitos.'];
        }
        if ($imageFile->size > 2097152) { // 2MB
            return ['error' => 'O tamanho da imagem excede o limite de 2MB.'];
        }

        $filePath = 'uploads/' . uniqid() . '.' . $imageFile->extension;

        try {
            $stream = fopen($imageFile->tempName, 'r+');
            $this->filesystem->writeStream($filePath, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
            return $filePath;
        } catch (\Exception $e) {
            Yii::error('Erro ao fazer upload da imagem: ' . $e->getMessage());
            throw new \RuntimeException('Não foi possível fazer upload da imagem.');
        }
    }
}

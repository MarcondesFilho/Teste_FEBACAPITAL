<?php

namespace src\Infrastructure\Services;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use yii\web\UploadedFile;

class S3UploadService
{
    private Filesystem $filesystem;

    public function __construct()
    {
        $client = new S3Client([
            'credentials' => [
                'key'    => 'AWS_ACCESS_KEY_ID',
                'secret' => 'AWS_SECRET_ACCESS_KEY',
            ],
            'region' => 'AWS_REGION',
            'version' => 'latest',
        ]);

        $adapter = new AwsS3V3Adapter($client, 'bucket-name');
        $this->filesystem = new Filesystem($adapter);
    }

    public function upload(UploadedFile $file): string
    {
        if (!$this->validateFile($file)) {
            throw new \Exception("Invalid file format or size");
        }

        $filePath = 'uploads/' . uniqid() . '.' . $file->extension;

        $stream = fopen($file->tempName, 'r+');
        $this->filesystem->writeStream($filePath, $stream);
        fclose($stream);

        return $filePath;  // Retorna o caminho salvo no S3
    }

    private function validateFile(UploadedFile $file): bool
    {
        $allowedExtensions = ['jpg', 'png'];
        $maxFileSize = 2 * 1024 * 1024;  // 2MB

        return in_array($file->extension, $allowedExtensions) && $file->size <= $maxFileSize;
    }
}

<?php

declare(strict_types=1);

namespace Infrastructure\Domain\Todo\Service;

use Domain\Todo\Service\PhotoUploaderInterface;
use Psr\Http\Message\UploadedFileInterface;
use Ramsey\Uuid\Uuid;

final class PhotoUploader implements PhotoUploaderInterface
{
    private string $path;

    /**
     * PhotoUploader constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function upload(UploadedFileInterface $file): string
    {
        $name = $this->generateName($file);
        $file->moveTo($this->path . '/' . $name);
        return $name;
    }

    private function generateName(UploadedFileInterface $file): string
    {
        $ext = pathinfo($file->getClientFilename() ?? '', PATHINFO_EXTENSION) ?: 'jpg';
        $name = Uuid::uuid4()->toString();
        return $name . '.' . $ext;
    }
}

<?php

declare(strict_types=1);

namespace Domain\Model\Todo\Service;

use Psr\Http\Message\UploadedFileInterface;

interface PhotoUploaderInterface
{
    public function upload(UploadedFileInterface $photo): string;
}

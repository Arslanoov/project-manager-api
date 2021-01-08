<?php

declare(strict_types=1);

namespace Infrastructure\Domain\Todo\Service;

use Domain\Todo\Service\PhotoRemoverInterface;

final class PhotoRemover implements PhotoRemoverInterface
{
    private string $path;

    /**
     * PhotoRemover constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function remove(string $name): void
    {
        if (file_exists($this->path . '/' . $name)) {
            unlink($this->path . '/' . $name);
        }
    }
}

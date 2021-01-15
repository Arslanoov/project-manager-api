<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Person;

final class BackgroundPhoto
{
    public const MEDIA_TYPES = [
        'image/jpeg',
        'image/png'
    ];

    private string $path;

    /**
     * BackgroundPhoto constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}

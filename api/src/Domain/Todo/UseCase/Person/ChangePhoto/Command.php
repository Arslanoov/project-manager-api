<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Person\ChangePhoto;

use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var UploadedFileInterface
     * @Assert\NotBlank()
     */
    public UploadedFileInterface $file;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public string $personId;

    /**
     * Command constructor.
     * @param UploadedFileInterface $file
     * @param string $personId
     */
    public function __construct(UploadedFileInterface $file, string $personId)
    {
        $this->file = $file;
        $this->personId = $personId;
    }
}

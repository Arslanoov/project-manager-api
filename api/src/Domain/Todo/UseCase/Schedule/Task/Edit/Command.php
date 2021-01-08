<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Edit;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public string $id;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=128, allowEmptyString=true)
     */
    public string $name;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=4, max=16, allowEmptyString=true)
     */
    public string $importantLevel;
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public string $description;

    /**
     * Command constructor.
     * @param string $id
     * @param string $name
     * @param string $importantLevel
     * @param string $description
     */
    public function __construct(string $id, string $name, string $importantLevel, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->importantLevel = $importantLevel;
        $this->description = $description;
    }
}

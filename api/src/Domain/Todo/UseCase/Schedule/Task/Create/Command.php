<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Create;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public string $scheduleId;
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
     * @var string|null
     */
    public ?string $description = null;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=4, max=16, allowEmptyString=true)
     */
    public string $importantLevel;

    /**
     * Command constructor.
     * @param string $scheduleId
     * @param string $id
     * @param string $name
     * @param string|null $description
     * @param string $importantLevel
     */
    public function __construct(
        string $scheduleId,
        string $id,
        string $name,
        ?string $description,
        string $importantLevel
    ) {
        $this->scheduleId = $scheduleId;
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->importantLevel = $importantLevel;
    }
}

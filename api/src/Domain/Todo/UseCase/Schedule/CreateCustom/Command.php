<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\CreateCustom;

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
     * @Assert\Uuid()
     */
    public string $personId;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=32, allowEmptyString=true)
     */
    public string $name;

    /**
     * Command constructor.
     * @param string $id
     * @param string $personId
     * @param string $name
     */
    public function __construct(string $id, string $personId, string $name)
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->name = $name;
    }
}

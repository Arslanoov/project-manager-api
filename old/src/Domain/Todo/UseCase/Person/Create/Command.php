<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Person\Create;

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
     * @Assert\Length(min=5, max=32, allowEmptyString=true)
     */
    public string $login;

    /**
     * Command constructor.
     * @param string $id
     * @param string $login
     */
    public function __construct(string $id, string $login)
    {
        $this->id = $id;
        $this->login = $login;
    }
}

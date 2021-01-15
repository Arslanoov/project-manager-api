<?php

declare(strict_types=1);

namespace Domain\User\UseCase\User\SignUp\Confirm;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=30, max=64)
     */
    public string $token;

    /**
     * Command constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }
}

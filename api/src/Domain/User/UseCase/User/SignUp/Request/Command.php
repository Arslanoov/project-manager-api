<?php

declare(strict_types=1);

namespace Domain\User\UseCase\User\SignUp\Request;

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
     * @Assert\Length(min=4, max=32, allowEmptyString=true)
     */
    public string $login;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=32, allowEmptyString=true)
     * @Assert\Email()
     */
    public string $email;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=32, allowEmptyString=true)
     */
    public string $password;

    /**
     * Command constructor.
     * @param string $id
     * @param string $login
     * @param string $email
     * @param string $password
     */
    public function __construct(string $id, string $login, string $email, string $password)
    {
        $this->id = $id;
        $this->login = $login;
        $this->email = $email;
        $this->password = $password;
    }
}

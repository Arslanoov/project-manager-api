<?php

declare(strict_types=1);

namespace Domain\User\Service;

use Domain\User\Entity\User\ConfirmToken;
use Domain\User\Entity\User\Email;
use Domain\User\Entity\User\Login;

interface SignUpConfirmSender
{
    public function send(Login $login, Email $email, ConfirmToken $token): void;
}

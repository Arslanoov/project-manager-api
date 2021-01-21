<?php

declare(strict_types=1);

namespace Domain\Model\User\Service;

use Domain\Model\User\Entity\User\ConfirmToken;
use Domain\Model\User\Entity\User\Email;
use Domain\Model\User\Entity\User\Login;

interface SignUpConfirmSender
{
    public function send(Login $login, Email $email, ConfirmToken $token): void;
}

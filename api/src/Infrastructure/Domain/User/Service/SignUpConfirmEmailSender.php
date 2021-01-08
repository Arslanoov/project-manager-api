<?php

declare(strict_types=1);

namespace Infrastructure\Domain\User\Service;

use Domain\User\Entity\User\ConfirmToken;
use Domain\User\Entity\User\Email;
use Domain\User\Entity\User\Login;
use Domain\User\Service\SignUpConfirmSender;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;
use RuntimeException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class SignUpConfirmEmailSender implements SignUpConfirmSender
{
    private Swift_Mailer $mailer;
    private Environment $twig;

    public function __construct(Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param Login $login
     * @param Email $email
     * @param ConfirmToken $token
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws RuntimeException
     */
    public function send(Login $login, Email $email, ConfirmToken $token): void
    {
        $message = (new Swift_Message('Sign Up Confirmation'))
            ->setTo($email->getValue())
            ->setBody(
                $this->twig->render('mail/auth/sign-up/confirm.html.twig', [
                    'token' => $token
                ]),
                'text/html'
            );

        if ($this->mailer->send($message) === 0) {
            throw new RuntimeException('Unable to send email.');
        }
    }
}

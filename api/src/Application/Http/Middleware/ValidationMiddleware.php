<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exception\ValidationException;
use App\Http\Response\ResponseFactory;
use JetBrains\PhpStorm\Pure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationMiddleware implements MiddlewareInterface
{
    private ResponseFactory $response;

    /**
     * ValidationMiddleware constructor.
     * @param ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            $violations = $this->errors($e->getViolations());
            return $this->response->json([
                'error' => $this->error($violations),
                'errors' => $violations
            ], 422);
        }
    }

    private function errors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            /** @var string $message */
            $message = $violation->getMessage();
            $errors[$violation->getPropertyPath()] = $message;
        }

        return $errors;
    }

    #[Pure]
    private function error(array $violationsArray): string
    {
        /** @var string $fieldError */
        $fieldError = array_keys($violationsArray)[0];
        /** @var string $error */
        $error = $violationsArray[$fieldError];
        return ucfirst($fieldError) . ' - ' . $error;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exception\ValidationException;
use Framework\Http\Psr7\ResponseFactory;
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
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }

    private function error(array $violationsArray): string
    {
        $fieldError = array_keys($violationsArray)[0];
        return ucfirst($fieldError) . ' - ' . $violationsArray[$fieldError];
    }
}

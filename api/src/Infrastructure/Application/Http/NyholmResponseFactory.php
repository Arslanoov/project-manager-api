<?php

declare(strict_types=1);

namespace Infrastructure\Application\Http;

use App\Http\Response\ResponseFactory;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final class NyholmResponseFactory implements ResponseFactory
{
    public function json(array $data, int $code = 200): ResponseInterface
    {
        return new Response($code, [], json_encode($data));
    }
}

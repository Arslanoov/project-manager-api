<?php

declare(strict_types=1);

namespace App\Http\Response;

use Psr\Http\Message\ResponseInterface;

interface ResponseFactory
{
    public function json(array $data, int $code = 200): ResponseInterface;
}

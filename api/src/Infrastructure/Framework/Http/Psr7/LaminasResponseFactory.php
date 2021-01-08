<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Http\Psr7;

use Framework\Http\Psr7\ResponseFactory;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

final class LaminasResponseFactory implements ResponseFactory
{
    public function html(string $html, int $code = 200): ResponseInterface
    {
        return new HtmlResponse($html, $code);
    }

    public function json(array $data, int $code = 200): ResponseInterface
    {
        return new JsonResponse($data, $code);
    }

    /**
     * @param mixed $data
     * @param int $code
     * @return ResponseInterface
     */
    public function simple($data = null, int $code = 200): ResponseInterface
    {
        if ($data) {
            return new Response($data, $code);
        }

        return new Response();
    }

    /**
     * @param mixed $data
     * @param int $code
     * @return ResponseInterface
     */
    public function xml($data, int $code = 200): ResponseInterface
    {
        return new Response\XmlResponse($data, $code);
    }

    public function text(string $text, int $code = 200): ResponseInterface
    {
        return new Response\TextResponse($text, $code);
    }

    public function empty(): ResponseInterface
    {
        return new Response\EmptyResponse();
    }
}

<?php

declare(strict_types=1);

namespace Infrastructure\Frontend\Service;

use Frontend\Service\FrontendUrlBuilderInterface;

final class FrontendUrlBuilder implements FrontendUrlBuilderInterface
{
    private string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function build(string $uri, array $params = []): string
    {
        return
            $this->baseUrl
            . ($uri ? '/' . $uri : '')
            . ($params ? '?' . http_build_query($params) : '');
    }
}

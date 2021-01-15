<?php

declare(strict_types=1);

namespace Frontend\Service;

interface FrontendUrlBuilderInterface
{
    public function build(string $uri, array $params = []): string;
}

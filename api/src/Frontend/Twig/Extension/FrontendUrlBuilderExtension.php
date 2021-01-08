<?php

declare(strict_types=1);

namespace Frontend\Twig\Extension;

use Frontend\Service\FrontendUrlBuilderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class FrontendUrlBuilderExtension extends AbstractExtension
{
    private FrontendUrlBuilderInterface $builder;

    public function __construct(FrontendUrlBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('frontend_url_build', [$this, 'build']),
        ];
    }

    public function build(string $path, array $params = []): string
    {
        return $this->builder->build($path, $params);
    }
}

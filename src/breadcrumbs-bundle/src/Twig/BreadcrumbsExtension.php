<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Twig;

use R1n0x\BreadcrumbsBundle\Builder;
use R1n0x\BreadcrumbsBundle\Context;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsExtension extends AbstractExtension
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly Builder $builder,
        private readonly Context $context
    ) {}

    /**
     * @return array<int, TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('build_breadcrumbs', function () {
                /* @phpstan-ignore argument.type */
                return $this->builder->build($this->requestStack->getCurrentRequest(), $this->context);
            }),
        ];
    }
}

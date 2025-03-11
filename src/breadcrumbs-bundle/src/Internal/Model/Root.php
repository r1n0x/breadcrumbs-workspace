<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class Root
{
    public function __construct(
        private string $name,
        private string $expression,
        private ?string $routeName
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }
}

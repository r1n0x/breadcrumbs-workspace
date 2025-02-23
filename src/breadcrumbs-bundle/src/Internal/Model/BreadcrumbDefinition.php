<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
abstract class BreadcrumbDefinition
{
    /**
     * @param array<int, string> $variables
     */
    public function __construct(
        private readonly ?string $routeName,
        private readonly string $expression,
        private readonly array $variables = []
    ) {}

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    /**
     * @return array<int, string>
     */
    public function getVariables(): array
    {
        return $this->variables;
    }
}

<?php

namespace R1n0x\BreadcrumbsBundle\Dao;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbDao
{
    public function __construct(
        private readonly string  $route,
        private readonly string  $expression,
        private readonly ?string $parentRoute,
        private readonly array   $variables = [],
        private readonly array   $parameters = []
    )
    {
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function getParentRoute(): ?string
    {
        return $this->parentRoute;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
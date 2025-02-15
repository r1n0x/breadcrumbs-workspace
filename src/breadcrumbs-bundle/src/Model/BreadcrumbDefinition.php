<?php

namespace R1n0x\BreadcrumbsBundle\Model;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbDefinition
{
    public function __construct(
        private readonly string  $routeName,
        private readonly string  $expression,
        private readonly ?string $parentRoute,
        private readonly array   $variables = [],
        private readonly array   $parameters = []
    )
    {
    }

    public function getRouteName(): string
    {
        return $this->routeName;
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
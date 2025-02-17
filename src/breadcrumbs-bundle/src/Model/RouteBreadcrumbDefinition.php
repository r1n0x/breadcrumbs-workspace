<?php

namespace R1n0x\BreadcrumbsBundle\Model;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteBreadcrumbDefinition extends BreadcrumbDefinition
{
    public function __construct(
        private readonly string  $routeName,
        string                   $expression,
        private readonly ?string $parentRoute,
        private readonly ?string $root,
        private readonly bool    $passParametersToExpression,
        private readonly array   $parameters = [],
        array                    $variables = []
    )
    {
        parent::__construct($expression, $variables);
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function getPassParametersToExpression(): bool
    {
        return $this->passParametersToExpression;
    }

    public function getRoot(): ?string
    {
        return $this->root;
    }

    public function getParentRoute(): ?string
    {
        return $this->parentRoute;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
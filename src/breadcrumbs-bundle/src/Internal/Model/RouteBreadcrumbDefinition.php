<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

use Override;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteBreadcrumbDefinition extends BreadcrumbDefinition
{
    /**
     * @param array<int, ParameterDefinition> $parameters
     * @param array<int, string> $variables
     */
    public function __construct(
        string $routeName,
        string $expression,
        private readonly ?string $parentRoute,
        private readonly ?string $root,
        private readonly bool $passParametersToExpression,
        private readonly array $parameters = [],
        array $variables = []
    ) {
        parent::__construct($routeName, $expression, $variables);
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

    /**
     * @return array<int, ParameterDefinition>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    #[Override]
    public function getRouteName(): string
    {
        /* @phpstan-ignore return.type */
        return parent::getRouteName();
    }
}

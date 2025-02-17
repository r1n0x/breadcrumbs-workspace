<?php

namespace R1n0x\BreadcrumbsBundle\Model;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RootBreadcrumbDefinition extends BreadcrumbDefinition
{
    public function __construct(
        private readonly ?string $routeName,
        string                   $expression,
        private readonly string  $name,
        array                    $variables = []
    )
    {
        parent::__construct($expression, $variables);
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
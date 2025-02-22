<?php

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class Root
{
    public function __construct(
        private readonly string  $name,
        private readonly string  $expression,
        private readonly ?string $routeName
    )
    {
    }

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
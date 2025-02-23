<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class Parameter
{
    public function __construct(
        private readonly string $name,
        private readonly ?string $value,
        private readonly ?string $routeName = null
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }
}

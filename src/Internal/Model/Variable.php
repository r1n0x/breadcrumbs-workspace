<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class Variable
{
    public function __construct(
        private string $name,
        private mixed $value,
        private ?string $routeName = null
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }
}

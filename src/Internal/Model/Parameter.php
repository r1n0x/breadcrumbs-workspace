<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class Parameter
{
    public function __construct(
        private string $name,
        private ?string $routeName,
        private null|int|string $pathValue,
        private mixed $autowiredValue
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getPathValue(): null|int|string
    {
        return $this->pathValue;
    }

    public function getAutowiredValue(): mixed
    {
        return $this->autowiredValue;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }
}

<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ParameterDefinition
{
    public function __construct(
        private readonly string $name,
        // required for properly handling nullable default values
        private readonly bool $isOptional,
        private readonly null|int|string $optionalValue
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function isOptional(): bool
    {
        return $this->isOptional;
    }

    public function getOptionalValue(): null|int|string
    {
        return $this->optionalValue;
    }

    public function isOptionalValue(mixed $value): bool
    {
        return $this->isOptional() && $this->optionalValue === $value;
    }
}

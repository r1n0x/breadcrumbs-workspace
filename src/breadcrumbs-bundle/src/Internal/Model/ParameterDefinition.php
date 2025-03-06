<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ParameterDefinition
{
    public function __construct(
        private readonly string $name,
        // required for properly handling nullable default values
        private readonly bool $hasDefaultValue,
        private readonly null|int|string $defaultValue
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }

    public function getDefaultValue(): null|int|string
    {
        return $this->defaultValue;
    }

    public function isDefaultValue(mixed $value): bool
    {
        return $this->hasDefaultValue() && $this->defaultValue === $value;
    }
}

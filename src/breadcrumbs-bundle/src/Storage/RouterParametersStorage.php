<?php

namespace R1n0x\BreadcrumbsBundle\Storage;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouterParametersStorage
{
    /**
     * @var array<string, mixed>
     */
    private array $parameters = [];

    public function set(string $name, mixed $value): void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->parameters;
    }

    public function get(string $name): mixed
    {
        return $this->parameters[$name] ?? null;
    }
}
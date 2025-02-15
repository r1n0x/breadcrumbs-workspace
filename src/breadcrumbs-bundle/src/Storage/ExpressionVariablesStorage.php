<?php

namespace R1n0x\BreadcrumbsBundle\Storage;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionVariablesStorage
{
    /**
     * @var array<string, mixed>
     */
    private array $variables = [];

    public function set(string $name, mixed $value): void
    {
        $this->variables[$name] = $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->variables;
    }

    public function get(string $name): mixed
    {
        return $this->variables[$name] ?? null;
    }
}
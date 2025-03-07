<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model\Violation;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
abstract class Error
{
    /** @var array<int, string> */
    private array $names = [];

    public function __construct(
        private readonly ErrorType $type
    ) {}

    public function getType(): ErrorType
    {
        return $this->type;
    }

    public function addName(string $name): self
    {
        $this->names[] = $name;

        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function getNames(): array
    {
        return $this->names;
    }
}

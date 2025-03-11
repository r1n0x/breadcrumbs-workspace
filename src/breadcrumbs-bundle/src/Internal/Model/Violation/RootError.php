<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model\Violation;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final class RootError extends Error
{
    public function __construct(
        private readonly ErrorType $type,
        private readonly string $name
    ) {
        parent::__construct($this->type);
    }

    public function getName(): string
    {
        return $this->name;
    }
}

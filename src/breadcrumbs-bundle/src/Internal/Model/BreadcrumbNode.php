<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class BreadcrumbNode
{
    public function __construct(
        private BreadcrumbDefinition $definition,
        private ?BreadcrumbNode $parent
    ) {}

    public function getDefinition(): BreadcrumbDefinition
    {
        return $this->definition;
    }

    public function getParent(): ?BreadcrumbNode
    {
        return $this->parent;
    }
}

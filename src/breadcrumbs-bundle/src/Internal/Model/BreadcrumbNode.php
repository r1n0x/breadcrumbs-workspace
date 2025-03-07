<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbNode
{
    public function __construct(
        private readonly BreadcrumbDefinition $definition,
        private readonly ?BreadcrumbNode $parent
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

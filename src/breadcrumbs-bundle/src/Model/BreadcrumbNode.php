<?php

namespace R1n0x\BreadcrumbsBundle\Model;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbNode
{
    public function __construct(
        private readonly BreadcrumbDefinition $definition,
        private readonly ?BreadcrumbNode      $child
    )
    {
    }

    public function getDefinition(): BreadcrumbDefinition
    {
        return $this->definition;
    }

    public function getChild(): ?BreadcrumbNode
    {
        return $this->child;
    }
}
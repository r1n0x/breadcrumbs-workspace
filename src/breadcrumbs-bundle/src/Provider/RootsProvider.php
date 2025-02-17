<?php

namespace R1n0x\BreadcrumbsBundle\Provider;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Model\Root;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RootsProvider
{
    public function __construct(
        private readonly array $roots
    )
    {
    }

    /**
     * @return array<int, Root>
     */
    public function getRoots(): array
    {
        $ret = [];
        foreach ($this->roots as $name => $root) {
            $ret[] = new Root($name, $root[Route::EXPRESSION], $root['route']);
        }
        return $ret;
    }
}
<?php

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Model\Root;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RootsResolver
{
    private ?array $roots = null;

    public function __construct(
        private readonly array $rootsConfig
    ) {}

    /**
     * @return array<int, Root>
     */
    public function getRoots(): array
    {
        if (!$this->roots) {
            $this->initializeRoots();
        }

        return $this->roots;
    }

    private function initializeRoots(): void
    {
        $this->roots = [];
        foreach ($this->rootsConfig as $name => $root) {
            $this->roots[] = new Root($name, $root[Route::EXPRESSION], $root['route']);
        }
    }
}

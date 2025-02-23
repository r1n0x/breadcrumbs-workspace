<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Model\Root;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RootsResolver
{
    /** @var null|array<int, Root> */
    private ?array $roots = null;

    public function __construct(
        /** @var array<string, array<string, string>> */
        private readonly array $rootsConfig
    ) {}

    /**
     * @return array<int, Root>
     */
    public function getRoots(): array
    {
        if (null === $this->roots) {
            $this->initializeRoots();
        }

        /* @phpstan-ignore return.type */
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

<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Model\Root;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final class RootsResolver
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
        return $this->roots ??= $this->parseRoots();
    }

    /**
     * @return array<int, Root>
     */
    private function parseRoots(): array
    {
        $roots = [];
        foreach ($this->rootsConfig as $name => $root) {
            $roots[] = new Root($name, $root[Route::EXPRESSION], $root['route']);
        }

        return $roots;
    }
}

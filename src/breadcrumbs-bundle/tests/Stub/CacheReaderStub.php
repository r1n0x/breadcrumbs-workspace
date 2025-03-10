<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Stub;

use Exception;
use R1n0x\BreadcrumbsBundle\Internal\CacheReaderInterface;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Tests\Provider\NodeSerializerProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class CacheReaderStub implements CacheReaderInterface
{
    /** @var array<int, BreadcrumbNode> */
    private array $nodes = [];

    public static function create(): CacheReaderStub
    {
        return new CacheReaderStub();
    }

    public function addNode(BreadcrumbNode $node): static
    {
        $this->nodes[] = $node;

        return $this;
    }

    public function write(string $cacheDir, string $contents): void
    {
        throw new Exception('NOT IMPLEMENTED FOR TESTING');
    }

    public function read(string $cacheDir): string
    {
        return NodeSerializerProvider::create()->serialize($this->nodes);
    }
}

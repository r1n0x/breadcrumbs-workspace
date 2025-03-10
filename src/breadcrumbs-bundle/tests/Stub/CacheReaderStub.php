<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Stub;

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

    private string $writeContents;

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
        $this->writeContents = $contents;
    }

    public function getWriteContents(): string
    {
        return $this->writeContents;
    }

    public function read(string $cacheDir): string
    {
        return NodeSerializerProvider::create()->serialize($this->nodes);
    }
}

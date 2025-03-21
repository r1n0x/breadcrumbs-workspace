<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Internal\CacheReaderInterface;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final class NodesResolver
{
    /**
     * @var null|array<int, BreadcrumbNode>
     */
    private ?array $nodes = null;

    public function __construct(
        private readonly CacheReaderInterface $cacheReader,
        private readonly NodeSerializer $serializer,
        private readonly string $cacheDir
    ) {}

    public function get(string $routeName): ?BreadcrumbNode
    {
        foreach ($this->all() as $node) {
            if ($node->getDefinition()->getRouteName() === $routeName) {
                return $node;
            }
        }

        return null;
    }

    /**
     * @return array<int, BreadcrumbNode>
     */
    public function all(): array
    {
        return $this->nodes ??= $this->getNodes();
    }

    /**
     * @return array<int, BreadcrumbNode>
     */
    private function getNodes(): array
    {
        $nodes = $this->cacheReader->read($this->cacheDir);

        return $this->serializer->deserialize($nodes);
    }
}

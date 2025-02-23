<?php

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Internal\CacheReader;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodesResolver
{
    /**
     * @var null|array<int, BreadcrumbNode>
     */
    private ?array $nodes = null;

    public function __construct(
        private readonly CacheReader $cacheReader,
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
        if (!$this->nodes) {
            $this->initializeNodes();
        }

        /* @phpstan-ignore return.type */
        return $this->nodes;
    }

    public function initializeNodes(): void
    {
        $nodes = $this->cacheReader->read($this->cacheDir);
        $this->nodes = $this->serializer->deserialize($nodes);
    }
}

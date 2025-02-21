<?php

namespace R1n0x\BreadcrumbsBundle\Resolver;

use R1n0x\BreadcrumbsBundle\CacheReader;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Serializer\NodeSerializer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbNodesResolver
{
    private ?array $nodes = null;

    public function __construct(
        private readonly string         $cacheDir,
        private readonly CacheReader    $pathFactory,
        private readonly NodeSerializer $serializer
    )
    {
    }

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
            $serializedNodes = $this->pathFactory->read($this->cacheDir);
            $this->nodes = $this->serializer->deserialize($serializedNodes);
        }
        return $this->nodes;
    }
}
<?php

namespace R1n0x\BreadcrumbsBundle\Resolver;

use R1n0x\BreadcrumbsBundle\Exception\FileAccessException;
use R1n0x\BreadcrumbsBundle\Factory\CachePathFactory;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Serializer\NodeSerializer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbNodesResolver
{
    private ?array $nodes = null;

    public function __construct(
        private readonly string           $cacheDir,
        private readonly CachePathFactory $pathFactory,
        private readonly NodeSerializer   $serializer
    )
    {
    }

    public function getNode(string $routeName): ?BreadcrumbNode
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
    private function all(): array
    {
        if (!$this->nodes) {
            $serializedNodes = file_get_contents($this->pathFactory->getFileCachePath($this->cacheDir));
            if ($serializedNodes === false) {
                throw new FileAccessException('Breadcrumbs couldn\'t be loaded from cache');
            }
            $this->nodes = $this->serializer->deserialize($serializedNodes);
        }
        return $this->nodes;
    }
}
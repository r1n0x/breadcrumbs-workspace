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
    public function all(): array
    {
        if (!$this->nodes) {
            $filePath = $this->pathFactory->getFileCachePath($this->cacheDir);
            $serializedNodes = @file_get_contents($filePath);
            if ($serializedNodes === false) {
                throw new FileAccessException(sprintf(
                    'Breadcrumbs couldn\'t be loaded from cache file (%s) - maybe you\'ve enabled debugging and stopped it before cache warmer could finish? If yes, rebuild cache.',
                    $filePath
                ));
            }
            $this->nodes = $this->serializer->deserialize($serializedNodes);
        }
        return $this->nodes;
    }
}
<?php

namespace R1n0x\BreadcrumbsBundle\CacheWarmer;

use R1n0x\BreadcrumbsBundle\Internal\CacheReader;
use R1n0x\BreadcrumbsBundle\Internal\DefinitionToNodeTransformer;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\DefinitionsResolver;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsCacheWarmer implements CacheWarmerInterface
{
    public function __construct(
        private readonly NodeSerializer $serializer,
        private readonly DefinitionToNodeTransformer $transformer,
        private readonly CacheReader $cacheReader,
        private readonly DefinitionsResolver $resolver,
    ) {}

    public function isOptional(): bool
    {
        return false;
    }

    public function warmUp(string $cacheDir): array
    {
        $definitions = $this->resolver->getDefinitions();
        $nodes = $this->transform($definitions);
        $this->cacheReader->write($cacheDir, $this->serializer->serialize($nodes));

        return [];
    }

    /**
     * @param array<int, BreadcrumbDefinition> $definitions
     *
     * @return array<int, BreadcrumbNode>
     */
    public function transform(array $definitions): array
    {
        $nodes = [];
        foreach ($definitions as $definition) {
            if ($definition instanceof RootBreadcrumbDefinition) {
                continue;
            }
            /* @phpstan-ignore argument.type */
            $nodes[] = $this->transformer->transform($definition, $definitions);
        }

        return $nodes;
    }
}

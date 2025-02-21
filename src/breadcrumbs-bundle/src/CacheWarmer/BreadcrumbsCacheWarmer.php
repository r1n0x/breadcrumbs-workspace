<?php

namespace R1n0x\BreadcrumbsBundle\CacheWarmer;


use R1n0x\BreadcrumbsBundle\Cache\CacheReaderInterface;
use R1n0x\BreadcrumbsBundle\CacheReader;
use R1n0x\BreadcrumbsBundle\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Resolver\DefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Serializer\NodeSerializer;
use R1n0x\BreadcrumbsBundle\Transformer\DefinitionToNodeTransformer;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsCacheWarmer implements CacheWarmerInterface
{
    public function __construct(
        private readonly NodeSerializer              $serializer,
        private readonly DefinitionToNodeTransformer $transformer,
        private readonly CacheReader                 $cacheReader,
        private readonly DefinitionsResolver         $resolver,
    )
    {
    }

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
     * @param array $definitions
     * @return array
     */
    public function transform(array $definitions): array
    {
        $nodes = [];
        foreach ($definitions as $definition) {
            if ($definition instanceof RootBreadcrumbDefinition) {
                continue;
            }
            /** @var RouteBreadcrumbDefinition $definition */
            $nodes[] = $this->transformer->transform($definition, $definitions);
        }
        return $nodes;
    }
}
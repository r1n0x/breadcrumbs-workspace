<?php

namespace R1n0x\BreadcrumbsBundle\Transformer;

use R1n0x\BreadcrumbsBundle\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbNode;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbDefinitionToNodeTransformer
{
    /**
     * @param string $routeName
     * @param array<int, BreadcrumbDefinition> $definitions
     * @return BreadcrumbNode|null
     */
    public function transform(string $routeName, array $definitions): ?BreadcrumbNode
    {
        return $this->doTransform($routeName, $definitions);
    }

    private function doTransform(string $routeName, array $definitions): ?BreadcrumbNode
    {
        $definition = $this->getDefinition($routeName, $definitions);
        if (!$definition) {
            return null;
        }
        $childDefinition = $definition->getParentRoute()
            ? $this->doTransform($definition->getParentRoute(), $definitions)
            : null;
        return new BreadcrumbNode(
            $definition,
            $childDefinition
        );
    }

    /**
     * @param string $routeName
     * @param array<int, BreadcrumbDefinition> $definitions
     * @return BreadcrumbDefinition|null
     */
    private function getDefinition(string $routeName, array $definitions): ?BreadcrumbDefinition
    {
        foreach ($definitions as $definition) {
            if ($definition->getRouteName() === $routeName) {
                return $definition;
            }
        }
        return null;
    }
}
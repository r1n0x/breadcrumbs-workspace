<?php

namespace R1n0x\BreadcrumbsBundle\Transformer;

use R1n0x\BreadcrumbsBundle\Exception\UnknownRootException;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Model\RouteBreadcrumbDefinition;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class DefinitionToNodeTransformer
{
    /**
     * @param RouteBreadcrumbDefinition $definition
     * @param array<int, BreadcrumbDefinition> $definitions
     * @return BreadcrumbNode
     * @throws UnknownRootException
     */
    public function transform(RouteBreadcrumbDefinition $definition, array $definitions): BreadcrumbNode
    {
        return $this->doTransform($definition, $definitions);
    }

    /**
     * @throws UnknownRootException
     */
    private function doTransform(?BreadcrumbDefinition $definition, array $definitions): BreadcrumbNode
    {
        $parentDefinition = match (true) {
            $definition instanceof RouteBreadcrumbDefinition => $this->getParentDefinition($definition, $definitions),
            default => null
        };
        return new BreadcrumbNode(
            $definition,
            $parentDefinition
        );
    }

    /**
     * @param RouteBreadcrumbDefinition $definition
     * @param array<int, BreadcrumbDefinition> $definitions
     * @return BreadcrumbNode|null
     * @throws UnknownRootException
     */
    public function getParentDefinition(RouteBreadcrumbDefinition $definition, array $definitions): ?BreadcrumbNode
    {
        $parentRoute = $definition->getParentRoute();
        if ($parentRoute) {
            $parentRouteDefinition = $this->getRouteDefinition($parentRoute, $definitions);
            if (!$parentRouteDefinition) {
                return new BreadcrumbNode(
                    $definition,
                    null
                );
            }
            return $this->doTransform($parentRouteDefinition, $definitions);
        }
        $rootName = $definition->getRoot();
        if ($rootName) {
            $rootDefinition = $this->getRootDefinition($rootName, $definitions);
            if (!$rootDefinition) {
                throw new UnknownRootException(sprintf(
                    'Referenced root "%s" for route "%s" doesn\'t exist',
                    $rootName,
                    $definition->getRouteName()
                ));
            }
            return $this->doTransform($rootDefinition, []);
        }
        return null;
    }

    /**
     * @param string $routeName
     * @param array<int, BreadcrumbDefinition> $definitions
     * @return BreadcrumbDefinition|null
     */
    private function getRouteDefinition(string $routeName, array $definitions): ?RouteBreadcrumbDefinition
    {
        foreach ($definitions as $definition) {
            if ($definition instanceof RouteBreadcrumbDefinition && $definition->getRouteName() === $routeName) {
                return $definition;
            }
        }
        return null;
    }

    /**
     * @param string $rootName
     * @param array<int, BreadcrumbDefinition> $definitions
     * @return RootBreadcrumbDefinition|null
     */
    private function getRootDefinition(string $rootName, array $definitions): ?RootBreadcrumbDefinition
    {
        foreach ($definitions as $definition) {
            if ($definition instanceof RootBreadcrumbDefinition && $definition->getName() === $rootName) {
                return $definition;
            }
        }
        return null;
    }
}
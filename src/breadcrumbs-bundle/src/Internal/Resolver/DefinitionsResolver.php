<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Exception\InvalidConfigurationException;
use R1n0x\BreadcrumbsBundle\Exception\RouteValidationException;
use R1n0x\BreadcrumbsBundle\Exception\VariablesResolverException;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class DefinitionsResolver
{
    public function __construct(
        private readonly RouteDefinitionsResolver $routeDefinitionsResolver,
        private readonly RootDefinitionsResolver $rootDefinitionsResolver
    ) {}

    /**
     * @return array<int, BreadcrumbDefinition>
     *
     * @throws RouteValidationException
     * @throws InvalidConfigurationException
     * @throws VariablesResolverException
     */
    public function getDefinitions(): array
    {
        return array_merge(
            $this->routeDefinitionsResolver->getDefinitions(),
            $this->rootDefinitionsResolver->getDefinitions()
        );
    }
}

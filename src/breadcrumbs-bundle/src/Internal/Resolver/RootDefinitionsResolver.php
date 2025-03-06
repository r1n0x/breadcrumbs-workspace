<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Exception\InvalidConfigurationException;
use R1n0x\BreadcrumbsBundle\Exception\VariablesResolverException;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RootDefinitionsResolver
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly VariablesResolver $variablesResolver,
        private readonly ParametersResolver $parametersResolver,
        private readonly RootsResolver $rootsResolver
    ) {}

    /**
     * @return array<int, RootBreadcrumbDefinition>
     *
     * @throws InvalidConfigurationException
     * @throws VariablesResolverException
     */
    public function getDefinitions(): array
    {
        /** @var array<int, RootBreadcrumbDefinition> $definitions */
        $definitions = [];

        foreach ($this->rootsResolver->getRoots() as $root) {
            $expression = $root->getExpression();
            $routeName = $root->getRouteName();
            if (null !== $routeName) {
                $route = $this->router->getRouteCollection()->get($routeName);
                if (null === $route) {
                    throw new InvalidConfigurationException(sprintf(
                        'Route "%s" referenced in breadcrumbs root "%s" doesn\'t exist.',
                        $routeName,
                        $root->getName()
                    ));
                }
                if (count($this->parametersResolver->getParameters($route)) > 0) {
                    throw new InvalidConfigurationException(sprintf(
                        'Route "%s" referenced in breadcrumbs root "%s" cannot be dynamic.',
                        $routeName,
                        $root->getName()
                    ));
                }
            }
            $definitions[] = new RootBreadcrumbDefinition(
                $routeName,
                $expression,
                $root->getName(),
                $this->variablesResolver->getVariables($expression)
            );
        }

        return $definitions;
    }
}

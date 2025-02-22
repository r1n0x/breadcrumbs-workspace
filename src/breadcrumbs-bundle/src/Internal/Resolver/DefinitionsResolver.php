<?php

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Event\RouteInitializedEvent;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class DefinitionsResolver
{
    public function __construct(
        private readonly RouterInterface          $router,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly VariablesResolver        $variablesResolver,
        private readonly ParametersResolver       $parametersResolver,
        private readonly RouteValidator           $validator,
        private readonly RootsResolver            $rootsProvider,
        private readonly bool                     $passParametersToExpression
    )
    {
    }

    /**
     * @return array<int, BreadcrumbDefinition>
     */
    public function getDefinitions(): array
    {
        return array_merge($this->getRouteDefinitions(), $this->getRootDefinitions());
    }

    /**
     * @return array<int, RouteBreadcrumbDefinition>
     */
    public function getRouteDefinitions(): array
    {
        /** @var array<int, RouteBreadcrumbDefinition> $definitions */
        $definitions = [];

        // I've decided to use an inline listener in this case because I don't want to introduce any storage singleton
        // class which would be referenced only in this cache warmer and some listener, that would be silly and this way is way cleaner in my opinion.
        $listener = function (RouteInitializedEvent $event) use (&$definitions) {
            $route = $event->getRoute();
            $expression = $route->getBreadcrumb()[Route::EXPRESSION] ?? null;
            if (!$expression) {
                return;
            }
            $this->validator->validate($route);
            $definitions[] = new RouteBreadcrumbDefinition(
                $event->getRouteName(),
                $expression,
                $route->getBreadcrumb()[Route::PARENT_ROUTE] ?? null,
                $route->getBreadcrumb()[Route::ROOT] ?? null,
                $route->getBreadcrumb()[Route::PASS_PARAMETERS_TO_EXPRESSION] ?? $this->passParametersToExpression,
                $this->variablesResolver->getVariables($expression),
                $this->parametersResolver->getParameters($route->getPath())
            );
        };

        $this->dispatcher->addListener(RouteInitializedEvent::class, $listener);

        // initialize breadcrumbs (symfony internally initializes all routes when this is executed, which causes listener to be called)
        $this->router->getRouteCollection();

        $this->dispatcher->removeListener(RouteInitializedEvent::class, $listener);

        return $definitions;
    }

    /**
     * @return array<int, RootBreadcrumbDefinition>
     */
    public function getRootDefinitions(): array
    {
        /** @var array<int, RootBreadcrumbDefinition> $definitions */
        $definitions = [];

        foreach ($this->rootsProvider->getRoots() as $root) {
            $expression = $root->getExpression();
            $routeName = $root->getRouteName();
            if ($routeName) {
                $route = $this->router->getRouteCollection()->get($routeName);
                if (!$route) {
                    throw new InvalidConfigurationException(sprintf(
                        'Route "%s" referenced in breadcrumbs root "%s" doesn\'t exist.',
                        $routeName,
                        $root->getName()
                    ));
                }
                if (count($this->parametersResolver->getParameters($route->getPath())) > 0) {
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
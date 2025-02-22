<?php

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Event\RouteInitializedEvent;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteDefinitionsResolver
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly VariablesResolver $variablesResolver,
        private readonly ParametersResolver $parametersResolver,
        private readonly RouteValidator $validator,
        private readonly bool $passParametersToExpression
    ) {}

    /**
     * @return array<int, RouteBreadcrumbDefinition>
     */
    public function getDefinitions(): array
    {
        /** @var array<int, RouteBreadcrumbDefinition> $definitions */
        $definitions = [];

        foreach ($this->getRoutes() as $name => $route) {
            $expression = $route->getBreadcrumb()[Route::EXPRESSION] ?? null;
            if (!$expression) {
                continue;
            }
            $this->validator->validate($route);
            $definitions[] = new RouteBreadcrumbDefinition(
                $name,
                $expression,
                $route->getBreadcrumb()[Route::PARENT_ROUTE] ?? null,
                $route->getBreadcrumb()[Route::ROOT] ?? null,
                $route->getBreadcrumb()[Route::PASS_PARAMETERS_TO_EXPRESSION] ?? $this->passParametersToExpression,
                $this->variablesResolver->getVariables($expression),
                $this->parametersResolver->getParameters($route->getPath())
            );
        }

        return $definitions;
    }

    /**
     * @return array<int, Route>
     */
    public function getRoutes(): array
    {
        /** @var array<string, Route> $definitions */
        $routes = [];

        // I've decided to use an inline listener in this case because I don't want to introduce any storage singleton
        // class which would be referenced only in this cache warmer and some listener, that would be silly and this way is way cleaner in my opinion.
        $listener = function (RouteInitializedEvent $event) use (&$routes) {
            $routes[$event->getRouteName()] = $event->getRoute();
        };

        $this->dispatcher->addListener(RouteInitializedEvent::class, $listener);

        // initialize breadcrumbs (symfony internally initializes all routes when this is executed, which causes listener to be called)
        $this->router->getRouteCollection();

        $this->dispatcher->removeListener(RouteInitializedEvent::class, $listener);

        return $routes;
    }
}

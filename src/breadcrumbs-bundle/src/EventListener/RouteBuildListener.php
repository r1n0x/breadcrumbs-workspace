<?php

namespace R1n0x\BreadcrumbsBundle\EventListener;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;
use R1n0x\BreadcrumbsBundle\Event\RouteBuildEvent;
use R1n0x\BreadcrumbsBundle\Resolver\ExpressionVariablesResolver;
use R1n0x\BreadcrumbsBundle\Resolver\RouteParametersResolver;
use R1n0x\BreadcrumbsBundle\Storage\BreadcrumbsStorage;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteBuildListener
{
    public function __construct(
        private readonly BreadcrumbsStorage $storage,
        private readonly ExpressionVariablesResolver $variablesResolver,
        private readonly RouteParametersResolver $parametersResolver
    )
    {
    }

    public function __invoke(
        RouteBuildEvent $event
    ): void
    {
        $route = $event->getRoute();
        $expression = $route->getBreadcrumb()[Route::EXPRESSION] ?? null;
        if (!$expression) {
            return;
        }
        $routeName = $event->getRouteName();
        $this->storage->add(
            new BreadcrumbDao(
                $routeName,
                $expression,
                $route->getBreadcrumb()[Route::PARENT_ROUTE] ?? null,
                $this->variablesResolver->resolve($expression),
                $this->parametersResolver->resolve($route->getPath())
            )
        );
    }
}
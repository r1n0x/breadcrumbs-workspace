<?php

namespace R1n0x\BreadcrumbsBundle\EventListener;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\BreadcrumbsStorage;
use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;
use R1n0x\BreadcrumbsBundle\Event\RouteBuildEvent;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteBuildListener
{
    public function __construct(
        private readonly BreadcrumbsStorage $storage
    )
    {
    }

    public function __invoke(
        RouteBuildEvent $event
    ): void
    {
        $route = $event->getRoute();
        if (!isset($route->getBreadcrumb()[Route::EXPRESSION])) {
            return;
        }
        $this->storage->add(
            new BreadcrumbDao(
                $event->getRouteName(),
                $route->getBreadcrumb()[Route::EXPRESSION],
                $route->getBreadcrumb()[Route::PARENT_ROUTE] ?? null
            )
        );
    }
}
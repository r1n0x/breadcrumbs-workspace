<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Event\RouteInitializedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class RoutesProvider implements RoutesProviderInterface
{
    public function __construct(
        private RouterInterface $router,
        private EventDispatcherInterface $dispatcher,
    ) {}

    /**
     * @return array<string, Route>
     */
    public function getRoutes(): array
    {
        /** @var array<string, Route> $routes */
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

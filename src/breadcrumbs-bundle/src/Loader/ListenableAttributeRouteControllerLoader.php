<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Loader;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Event\RouteInitializedEvent;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Bundle\FrameworkBundle\Routing\AttributeRouteControllerLoader;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ListenableAttributeRouteControllerLoader extends AttributeRouteControllerLoader
{
    /** @phpstan-ignore property.uninitialized */
    private EventDispatcherInterface $dispatcher;

    public function setDispatcher(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @phpstan-ignore missingType.generics, missingType.iterableValue
     */
    protected function addRoute(RouteCollection $collection, object $annot, array $globals, ReflectionClass $class, ReflectionMethod $method): void
    {
        parent::addRoute($collection, $annot, $globals, $class, $method);
        if (!$annot instanceof Route) {
            return;
        }
        // As far is I know, after looking through symfony internals for FAR too long, this is the simplest way
        // to relate a route to breadcrumbs.
        //
        // Problems I encountered were:
        //
        // - one controller being able to have multiple routes, which can't be
        //   easily related to breadcrumbs later on (cannot use controller attribute as anchor)
        //
        // - symfony dynamic route name generation and being able to add prefixes to routes by putting Route
        //   attribute on class and adding sometimes adding indexes at the end of name (cannot use route name attribute as anchor)
        //
        // - surely, there is more ways to use Route attribute than I know (knowing Symfony) and I just don't want
        //   to copy every single thing to this bundle, when I can just add a listener here and have Symfony leverage everything
        //
        // Obviously you can do it differently, but that would require copying far too much logic from symfony internals,
        // which I don't think is neither a good idea, neither sustainable.
        //
        // If you've read this thank you :)
        //
        // Huge thanks to Symfony for developing an awesome tool <3
        $this->dispatcher->dispatch(new RouteInitializedEvent(
            array_key_last($collection->all()), /* @phpstan-ignore argument.type */
            $annot
        ));
    }
}

<?php

namespace R1n0x\BreadcrumbsBundle;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Event\RouteBuildEvent;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class AttributeRouteControllerLoader extends \Symfony\Bundle\FrameworkBundle\Routing\AttributeRouteControllerLoader
{
    private EventDispatcherInterface $dispatcher;

    public function setDispatcher(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatcher = $dispatcher;
    }

    protected function addRoute(RouteCollection $collection, object $annot, array $globals, ReflectionClass $class, ReflectionMethod $method): void
    {
        parent::addRoute($collection, $annot, $globals, $class, $method);
        if (!($annot instanceof Route)) {
            return;
        }
        $this->dispatcher->dispatch(new RouteBuildEvent(
            array_key_last($collection->all()),
            $annot
        ));
    }
}
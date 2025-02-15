<?php

namespace R1n0x\BreadcrumbsBundle\Loader;

use R1n0x\BreadcrumbsBundle\Attribute\Route as BreadcrumbRoute;
use R1n0x\BreadcrumbsBundle\Breadcrumb;
use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[AsDecorator('routing.loader.attribute')]
class AttributeRouteControllerLoader extends \Symfony\Bundle\FrameworkBundle\Routing\AttributeRouteControllerLoader
{
    private BreadcrumbsStorage $storage;

    #[Required]
    public function setStorage(BreadcrumbsStorage $storage): void
    {
        $this->storage = $storage;
    }

    protected function addRoute(RouteCollection $collection, object $annot, array $globals, \ReflectionClass $class, \ReflectionMethod $method): void
    {
        parent::addRoute($collection, $annot, $globals, $class, $method);
        if (!($annot instanceof BreadcrumbRoute && $annot->getBreadcrumb()[Breadcrumb::LABEL])) {
            return;
        }
        $name = array_key_last($collection->all());
        $this->storage->add(
            new BreadcrumbDao(
                $name,
                $annot->getBreadcrumb()[Breadcrumb::LABEL],
                $annot->getBreadcrumb()[Breadcrumb::PARENT_ROUTE] ?? null
            )
        );
    }
}
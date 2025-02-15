<?php

namespace R1n0x\BreadcrumbsBundle\Resolver;

use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;
use R1n0x\BreadcrumbsBundle\Exception\RouteNotFoundException;
use R1n0x\BreadcrumbsBundle\Storage\BreadcrumbsStorage;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsResolver
{
    public function __construct(
        private readonly BreadcrumbsStorage $storage
    )
    {
    }


    /**
     * @param string $routeName
     * @return array<int, BreadcrumbDao>
     * @throws RouteNotFoundException
     */
    public function resolve(string $routeName): array
    {
        return array_reverse($this->getBreadcrumbs($routeName));
    }

    /**
     * @param string $routeName
     * @return array<int, BreadcrumbDao>
     * @throws RouteNotFoundException
     */
    public function getBreadcrumbs(string $routeName): array
    {
        $breadcrumb = $this->storage->get($routeName);
        if (!$breadcrumb) {
            throw new RouteNotFoundException(sprintf(
                'Route "%s" was not found',
                $routeName
            ));
        }
        if (!$breadcrumb->getParentRoute()) {
            return [$breadcrumb];
        }
        return [$breadcrumb, ...$this->getBreadcrumbs($breadcrumb->getParentRoute())];
    }
}
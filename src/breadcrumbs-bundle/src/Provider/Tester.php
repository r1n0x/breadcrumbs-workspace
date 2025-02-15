<?php

namespace R1n0x\BreadcrumbsBundle\Provider;

use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;
use R1n0x\BreadcrumbsBundle\Loader\BreadcrumbsStorage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class Tester
{
    public function __construct(
        private BreadcrumbsStorage $storage
    )
    {
    }

    public function test(Request $request): void
    {
        $routeName = $request->attributes->getString('_route');
        $breadcrumbs = $this->getBreadcrumbs($routeName);
        $route = $request;
    }

    /**
     * @param string $routeName
     * @return array<int, BreadcrumbDao>
     */
    public function getBreadcrumbs(string $routeName): array
    {
        $breadcrumb = $this->storage->get($routeName);
        if (!$breadcrumb) {
            return [];
        }
        if (!$breadcrumb->getParentRoute()) {
            return [$breadcrumb];
        }
        return [$breadcrumb, ...$this->getBreadcrumbs($breadcrumb->getParentRoute())];
    }
}
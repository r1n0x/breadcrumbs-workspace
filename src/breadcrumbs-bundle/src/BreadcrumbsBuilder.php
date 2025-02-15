<?php

namespace R1n0x\BreadcrumbsBundle;

use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsBuilder
{
    public function __construct(
        private readonly BreadcrumbsStorage $storage
    )
    {
    }

    public function build(Request $request): void
    {
        $routeName = $request->attributes->getString('_route');
        $breadcrumbs = array_reverse($this->getBreadcrumbs($routeName));
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
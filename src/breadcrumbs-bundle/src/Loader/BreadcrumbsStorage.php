<?php

namespace R1n0x\BreadcrumbsBundle\Loader;

use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsStorage
{
    /**
     * @var array<string, BreadcrumbDao>|null
     */
    private ?array $breadcrumbs = null;

    public function __construct(
        private readonly RouterInterface $router
    )
    {
    }

    public function add(BreadcrumbDao $breadcrumb): void
    {
        $this->breadcrumbs[$breadcrumb->getRoute()] = $breadcrumb;
    }

    /**
     * @return array<int, BreadcrumbDao>
     */
    public function all(): array
    {
        $this->initialize();
        return $this->breadcrumbs;
    }

    public function get(string $routeName): ?BreadcrumbDao
    {
        $this->initialize();
        return $this->breadcrumbs[$routeName] ?? null;
    }

    public function initialize(): void
    {
        if (!$this->breadcrumbs) {
            $this->router->getRouteCollection()->all();
        }
    }
}
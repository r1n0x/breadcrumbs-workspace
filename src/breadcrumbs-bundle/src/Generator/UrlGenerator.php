<?php

namespace R1n0x\BreadcrumbsBundle\Generator;

use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;
use R1n0x\BreadcrumbsBundle\Storage\RouterParametersStorage;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class UrlGenerator
{
    public function __construct(
        private readonly RouterParametersStorage $storage,
        private readonly RouterInterface         $router
    )
    {
    }

    public function generate(BreadcrumbDao $breadcrumb): string
    {
        return $this->router->generate($breadcrumb->getRoute(), $this->getParameters($breadcrumb));
    }

    /**
     * @param BreadcrumbDao $breadcrumb
     * @return array
     */
    public function getParameters(BreadcrumbDao $breadcrumb): array
    {
        $parameters = [];
        foreach($breadcrumb->getParameters() as $parameter) {
            $parameters[$parameter] = $this->storage->get($parameter);
        }
        return $parameters;
    }
}
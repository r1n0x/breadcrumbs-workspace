<?php

namespace R1n0x\BreadcrumbsBundle\Generator;

use R1n0x\BreadcrumbsBundle\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbDefinition;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class UrlGenerator
{
    public function __construct(
        private readonly ParametersHolder $holder,
        private readonly RouterInterface  $router
    )
    {
    }

    public function generate(BreadcrumbDefinition $breadcrumb): string
    {
        return $this->router->generate($breadcrumb->getRouteName(), $this->getParameters($breadcrumb));
    }

    /**
     * @param BreadcrumbDefinition $breadcrumb
     * @return array
     */
    public function getParameters(BreadcrumbDefinition $breadcrumb): array
    {
        $routeName = $breadcrumb->getRouteName();
        $parameters = [];
        foreach ($breadcrumb->getParameters() as $parameterName) {
            $parameters[$parameterName] = $this->holder->getValue($parameterName, $routeName) ?? $this->holder->getValue($parameterName);
        }
        return $parameters;
    }
}
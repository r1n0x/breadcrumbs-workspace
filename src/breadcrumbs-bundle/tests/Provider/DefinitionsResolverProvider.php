<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\Resolver\DefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootDefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RouteDefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RoutesProviderInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class DefinitionsResolverProvider
{
    public static function create(
        RoutesProviderInterface $provider,
        RouterInterface $router,
        RootsResolver $resolver
    ): DefinitionsResolver {
        return new DefinitionsResolver(
            new RouteDefinitionsResolver(
                $provider,
                VariablesResolverProvider::create(),
                ParametersResolverProvider::create(),
                RouteValidatorProvider::create(),
                true
            ),
            new RootDefinitionsResolver(
                $router,
                VariablesResolverProvider::create(),
                new ParametersResolver(),
                $resolver
            )
        );
    }
}

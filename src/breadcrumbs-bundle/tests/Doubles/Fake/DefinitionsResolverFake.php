<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

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
final readonly class DefinitionsResolverFake
{
    public static function create(
        RoutesProviderInterface $provider,
        RouterInterface $router,
        RootsResolver $resolver
    ): DefinitionsResolver {
        return new DefinitionsResolver(
            new RouteDefinitionsResolver(
                $provider,
                VariablesResolverFake::create(),
                ParametersResolverFake::create(),
                RouteValidatorFake::create(),
                true
            ),
            new RootDefinitionsResolver(
                $router,
                VariablesResolverFake::create(),
                new ParametersResolver(),
                $resolver
            )
        );
    }
}

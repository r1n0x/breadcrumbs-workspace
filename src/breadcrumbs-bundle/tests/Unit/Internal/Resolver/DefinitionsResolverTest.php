<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Resolver;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\DefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootDefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RouteDefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RoutesProviderInterface;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\VariablesResolver;
use R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ParametersResolverProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\RootsResolverProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\RouteValidatorProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\VariablesResolverProvider;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouterStub;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RoutesProviderStub;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(DefinitionsResolver::class)]
#[UsesClass(ParametersResolver::class)]
#[UsesClass(RootDefinitionsResolver::class)]
#[UsesClass(RootsResolver::class)]
#[UsesClass(RouteDefinitionsResolver::class)]
#[UsesClass(VariablesResolver::class)]
#[UsesClass(RouteValidator::class)]
class DefinitionsResolverTest extends TestCase
{
    #[Test]
    public function resolvesDefinitions(): void
    {
        $definitions = $this
            ->getService(
                RoutesProviderStub::create()
                    ->addRoute('route_name_1', '/products/1/details', [
                        Route::EXPRESSION => 'route_expression_1',
                    ])
                    ->addRoute('route_name_2', '/products/2/details', [
                        Route::EXPRESSION => 'route_expression_2',
                    ]),
                RouterStub::create(),
                RootsResolverProvider::createWithConfig([
                    'root_name' => [
                        Route::EXPRESSION => 'root_expression',
                        'route' => null,
                    ],
                ])
            )
            ->getDefinitions();

        $this->assertCount(3, $definitions);

        $this->assertInstanceOf(RouteBreadcrumbDefinition::class, $definitions[0]);
        $this->assertEquals('route_name_1', $definitions[0]->getRouteName());

        $this->assertInstanceOf(RouteBreadcrumbDefinition::class, $definitions[1]);
        $this->assertEquals('route_name_2', $definitions[1]->getRouteName());

        $this->assertInstanceOf(RootBreadcrumbDefinition::class, $definitions[2]);
        $this->assertEquals('root_name', $definitions[2]->getName());
    }

    private function getService(
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

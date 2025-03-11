<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Resolver;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Exception\InvalidConfigurationException;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootDefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\VariablesResolver;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\RootsResolverFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\VariablesResolverFake;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouterStub;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(RootDefinitionsResolver::class)]
#[UsesClass(RootsResolver::class)]
#[UsesClass(VariablesResolver::class)]
#[UsesClass(ParametersResolver::class)]
class RootDefinitionsResolverTest extends TestCase
{
    #[Test]
    #[TestDox('Throws exception, if root references unknown route name')]
    public function throwsExceptionIfRootReferencesUnknownRouteName(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(RootDefinitionsResolver::ERROR_CODE_UNKNOWN_ROUTE_NAME);

        $this
            ->getService(RouterStub::create(), RootsResolverFake::create([
                'root_name' => [
                    Route::EXPRESSION => Dummy::string(),
                    'route' => 'unknown_route_name',
                ],
            ]))
            ->getDefinitions();
    }

    #[Test]
    #[TestDox('Throws exception, if route referenced in root is dynamic')]
    public function throwsExceptionIfRouteReferencedInRootIsDynamic(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(RootDefinitionsResolver::ERROR_CODE_DYNAMIC_ROUTE);

        $this
            ->getService(
                RouterStub::create()
                    ->addRouteStub('route_name', '/route/{dynamic_parameter}'),
                RootsResolverFake::create([
                    'root_name' => [
                        Route::EXPRESSION => Dummy::string(),
                        'route' => 'route_name',
                    ],
                ])
            )
            ->getDefinitions();
    }

    #[Test]
    public function resolvesDefinitions(): void
    {
        $definitions = $this
            ->getService(
                RouterStub::create()
                    ->addRouteStub('route_name_1', '/route/static/1')
                    ->addRouteStub('route_name_2', '/route/static/2'),
                RootsResolverFake::create([
                    'root_name_1' => [
                        Route::EXPRESSION => 'variable_1 + variable_2',
                        'route' => 'route_name_1',
                    ],
                    'root_name_2' => [
                        Route::EXPRESSION => 'variable_3 + variable_4',
                        'route' => 'route_name_2',
                    ],
                ])
            )
            ->getDefinitions();

        $this->assertCount(2, $definitions);

        $this->assertEquals('root_name_1', $definitions[0]->getName());
        $this->assertEquals('route_name_1', $definitions[0]->getRouteName());
        $this->assertEquals(['variable_1', 'variable_2'], $definitions[0]->getVariables());
        $this->assertEquals('variable_1 + variable_2', $definitions[0]->getExpression());

        $this->assertEquals('root_name_2', $definitions[1]->getName());
        $this->assertEquals('route_name_2', $definitions[1]->getRouteName());
        $this->assertEquals(['variable_3', 'variable_4'], $definitions[1]->getVariables());
        $this->assertEquals('variable_3 + variable_4', $definitions[1]->getExpression());
    }

    private function getService(
        RouterInterface $router,
        RootsResolver $resolver
    ): RootDefinitionsResolver {
        return new RootDefinitionsResolver(
            $router,
            VariablesResolverFake::create(),
            new ParametersResolver(),
            $resolver
        );
    }
}

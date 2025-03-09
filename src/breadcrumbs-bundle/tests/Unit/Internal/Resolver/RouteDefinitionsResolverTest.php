<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Resolver;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RouteDefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RoutesProviderInterface;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\VariablesResolver;
use R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ParametersResolverProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\RouteValidatorProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\VariablesResolverProvider;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RoutesProviderStub;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(RouteDefinitionsResolver::class)]
#[UsesClass(ParametersResolver::class)]
#[UsesClass(VariablesResolver::class)]
#[UsesClass(RouteValidator::class)]
class RouteDefinitionsResolverTest extends TestCase
{
    #[Test]
    public function resolvesRouteDefinitions(): void
    {
        $definitions = $this
            ->getService(
                RoutesProviderStub::create()
                    ->addRoute('route_name_1', '/route/1/{dynamic_parameter_1}', [
                        Route::EXPRESSION => 'expression_1',
                    ])
                    ->addRoute('route_name_2', '/route/2/{dynamic_parameter_2}', [
                        Route::EXPRESSION => 'expression_2',
                        Route::PARENT_ROUTE => 'parent_route',
                    ])
                    ->addRoute('route_name_3', '/route/3/{dynamic_parameter_3}', [
                        Route::EXPRESSION => 'expression_3',
                        Route::ROOT => 'root',
                        Route::PASS_PARAMETERS_TO_EXPRESSION => false,
                    ])
                    ->addRoute('route_name_4', '/route/4/{dynamic_parameter_4}', [])
            )
            ->getDefinitions();

        $this->assertEquals('route_name_1', $definitions[0]->getRouteName());
        $this->assertEquals(['expression_1'], $definitions[0]->getVariables());
        $this->assertEquals('expression_1', $definitions[0]->getExpression());
        $this->assertEquals('dynamic_parameter_1', $definitions[0]->getParameters()[0]->getName());
        $this->assertFalse($definitions[0]->getParameters()[0]->isOptional());
        $this->assertEquals(null, $definitions[0]->getParameters()[0]->getOptionalValue());
        $this->assertEquals(null, $definitions[0]->getParentRoute());
        $this->assertTrue($definitions[0]->getPassParametersToExpression());
        $this->assertEquals(null, $definitions[0]->getRoot());

        $this->assertEquals('route_name_2', $definitions[1]->getRouteName());
        $this->assertEquals(['expression_2'], $definitions[1]->getVariables());
        $this->assertEquals('expression_2', $definitions[1]->getExpression());
        $this->assertEquals('dynamic_parameter_2', $definitions[1]->getParameters()[0]->getName());
        $this->assertFalse($definitions[1]->getParameters()[0]->isOptional());
        $this->assertEquals(null, $definitions[1]->getParameters()[0]->getOptionalValue());
        $this->assertEquals('parent_route', $definitions[1]->getParentRoute());
        $this->assertTrue($definitions[1]->getPassParametersToExpression());
        $this->assertEquals(null, $definitions[1]->getRoot());

        $this->assertEquals('route_name_3', $definitions[2]->getRouteName());
        $this->assertEquals(['expression_3'], $definitions[2]->getVariables());
        $this->assertEquals('expression_3', $definitions[2]->getExpression());
        $this->assertEquals('dynamic_parameter_3', $definitions[2]->getParameters()[0]->getName());
        $this->assertFalse($definitions[2]->getParameters()[0]->isOptional());
        $this->assertEquals(null, $definitions[2]->getParameters()[0]->getOptionalValue());
        $this->assertEquals(null, $definitions[2]->getParentRoute());
        $this->assertFalse($definitions[2]->getPassParametersToExpression());
        $this->assertEquals('root', $definitions[2]->getRoot());
    }

    private function getService(
        RoutesProviderInterface $provider
    ): RouteDefinitionsResolver {
        return new RouteDefinitionsResolver(
            $provider,
            VariablesResolverProvider::create(),
            ParametersResolverProvider::create(),
            RouteValidatorProvider::create(),
            true
        );
    }
}

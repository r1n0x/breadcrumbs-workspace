<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\CacheWarmer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\CacheWarmer\BreadcrumbsCacheWarmer;
use R1n0x\BreadcrumbsBundle\Internal\CacheReaderInterface;
use R1n0x\BreadcrumbsBundle\Internal\DefinitionToNodeTransformer;
use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\DefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootDefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RouteDefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RoutesProviderInterface;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\VariablesResolver;
use R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator;
use R1n0x\BreadcrumbsBundle\Tests\Provider\DefinitionsResolverProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\DefinitionToNodeTransformerProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\NodeSerializerProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\RootsResolverProvider;
use R1n0x\BreadcrumbsBundle\Tests\Stub\CacheReaderStub;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouterStub;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RoutesProviderStub;
use R1n0x\BreadcrumbsBundle\Tests\Unused;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(BreadcrumbsCacheWarmer::class)]
#[UsesClass(DefinitionToNodeTransformer::class)]
#[UsesClass(NodeSerializer::class)]
#[UsesClass(DefinitionsResolver::class)]
#[UsesClass(DefinitionsResolver::class)]
#[UsesClass(ParametersResolver::class)]
#[UsesClass(RootDefinitionsResolver::class)]
#[UsesClass(RootsResolver::class)]
#[UsesClass(RootsResolver::class)]
#[UsesClass(RouteDefinitionsResolver::class)]
#[UsesClass(VariablesResolver::class)]
#[UsesClass(RouteValidator::class)]
class BreadcrumbsCacheWarmerTest extends TestCase
{
    #[Test]
    public function warmsUpCache(): void
    {
        $cacheReader = CacheReaderStub::create();
        $service = $this
            ->getService(
                $cacheReader,
                RoutesProviderStub::create()
                    ->addRoute('route_name_1', '/products/1/details', [
                        Route::EXPRESSION => 'route_expression_1',
                        Route::PARENT_ROUTE => 'route_name_2',
                    ])
                    ->addRoute('route_name_2', '/products/2/details', [
                        Route::EXPRESSION => 'route_expression_2',
                        Route::ROOT => 'root_name',
                    ]),
                RouterStub::create(),
                RootsResolverProvider::createWithConfig([
                    'root_name' => [
                        Route::EXPRESSION => 'root_expression',
                        'route' => null,
                    ],
                ])
            );

        $this->assertFalse($service->isOptional());
        $service->warmUp(Unused::string());

        $this->assertEquals(
            [
                [
                    'definition' => [
                        NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                        'route' => 'route_name_1',
                        Route::EXPRESSION => 'route_expression_1',
                        Route::PARENT_ROUTE => 'route_name_2',
                        Route::ROOT => null,
                        Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                        'parameters' => [],
                        'variables' => [
                            'route_expression_1',
                        ],
                    ],
                    'parent' => [
                        'definition' => [
                            NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                            'route' => 'route_name_2',
                            Route::EXPRESSION => 'route_expression_2',
                            Route::ROOT => 'root_name',
                            Route::PARENT_ROUTE => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [
                                'route_expression_2',
                            ],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROOT,
                                'route' => null,
                                Route::EXPRESSION => 'root_expression',
                                'name' => 'root_name',
                                'variables' => [
                                    'root_expression',
                                ],
                            ],
                            'parent' => null,
                        ],
                    ],
                ],
                [
                    'definition' => [
                        NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                        'route' => 'route_name_2',
                        Route::EXPRESSION => 'route_expression_2',
                        Route::ROOT => 'root_name',
                        Route::PARENT_ROUTE => null,
                        Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                        'parameters' => [],
                        'variables' => [
                            'route_expression_2',
                        ],
                    ],
                    'parent' => [
                        'definition' => [
                            NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROOT,
                            'route' => null,
                            Route::EXPRESSION => 'root_expression',
                            'name' => 'root_name',
                            'variables' => [
                                'root_expression',
                            ],
                        ],
                        'parent' => null,
                    ],
                ],
            ],
            json_decode($cacheReader->getWriteContents(), true)
        );
    }

    private function getService(
        CacheReaderInterface $cacheReader,
        RoutesProviderInterface $routesProvider,
        RouterInterface $router,
        RootsResolver $resolver
    ): BreadcrumbsCacheWarmer {
        return new BreadcrumbsCacheWarmer(
            NodeSerializerProvider::create(),
            DefinitionToNodeTransformerProvider::create(),
            $cacheReader,
            DefinitionsResolverProvider::create(
                $routesProvider,
                $router,
                $resolver
            )
        );
    }
}

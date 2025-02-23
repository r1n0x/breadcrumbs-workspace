<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodeSerializerDataProvider
{
    public static function getSerializesTestScenarios(): array
    {
        return [
            'Tree with root node' => [
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'second_route',
                        "'2'",
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'first_route',
                            "'1'",
                            'second_route',
                            'root_name',
                            true,
                            [],
                            []
                        ),
                        new BreadcrumbNode(
                            new RootBreadcrumbDefinition(
                                null,
                                "'Root'",
                                'root_name',
                                []
                            ),
                            null
                        )
                    )
                ),
                json_encode([
                    [
                        'definition' => [
                            NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                            'route' => 'second_route',
                            Route::EXPRESSION => "'2'",
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'first_route',
                                Route::EXPRESSION => "'1'",
                                Route::PARENT_ROUTE => 'second_route',
                                Route::ROOT => 'root_name',
                                Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                                'parameters' => [],
                                'variables' => [],
                            ],
                            'parent' => [
                                'definition' => [
                                    NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROOT,
                                    'route' => null,
                                    Route::EXPRESSION => "'Root'",
                                    'name' => 'root_name',
                                    'variables' => [],
                                ],
                                'parent' => null,
                            ],
                        ],
                    ],
                ], JSON_PRETTY_PRINT),
            ],
            'Tree without root node' => [
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'second_route',
                        "'2'",
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'first_route',
                            "'1'",
                            'second_route',
                            'root_name',
                            true,
                            [],
                            []
                        ),
                        null
                    )
                ),
                json_encode([
                    [
                        'definition' => [
                            NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                            'route' => 'second_route',
                            Route::EXPRESSION => "'2'",
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'first_route',
                                Route::EXPRESSION => "'1'",
                                Route::PARENT_ROUTE => 'second_route',
                                Route::ROOT => 'root_name',
                                Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                                'parameters' => [],
                                'variables' => [],
                            ],
                            'parent' => null,
                        ],
                    ],
                ], JSON_PRETTY_PRINT),
            ],
            'Singular node' => [
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'second_route',
                        "'2'",
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    null
                ),
                json_encode([
                    [
                        'definition' => [
                            NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                            'route' => 'second_route',
                            Route::EXPRESSION => "'2'",
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => null,
                    ],
                ], JSON_PRETTY_PRINT),
            ],
        ];
    }

    public static function getDeserializesTestScenarios(): array
    {
        return [
            'Tree with root node' => [
                json_encode([
                    [
                        'definition' => [
                            NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                            'route' => 'second_route',
                            Route::EXPRESSION => "'2'",
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'first_route',
                                Route::EXPRESSION => "'1'",
                                Route::PARENT_ROUTE => 'second_route',
                                Route::ROOT => 'root_name',
                                Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                                'parameters' => [],
                                'variables' => [],
                            ],
                            'parent' => [
                                'definition' => [
                                    NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROOT,
                                    'route' => null,
                                    Route::EXPRESSION => "'Root'",
                                    'name' => 'root_name',
                                    'variables' => [],
                                ],
                                'parent' => null,
                            ],
                        ],
                    ],
                ], JSON_PRETTY_PRINT),
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'second_route',
                        "'2'",
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'first_route',
                            "'1'",
                            'second_route',
                            'root_name',
                            true,
                            [],
                            []
                        ),
                        new BreadcrumbNode(
                            new RootBreadcrumbDefinition(
                                null,
                                "'Root'",
                                'root_name',
                                []
                            ),
                            null
                        )
                    )
                ),
            ],
            'Tree without root node' => [
                json_encode([
                    [
                        'definition' => [
                            NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                            'route' => 'second_route',
                            Route::EXPRESSION => "'2'",
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'first_route',
                                Route::EXPRESSION => "'1'",
                                Route::PARENT_ROUTE => 'second_route',
                                Route::ROOT => 'root_name',
                                Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                                'parameters' => [],
                                'variables' => [],
                            ],
                            'parent' => null,
                        ],
                    ],
                ], JSON_PRETTY_PRINT),
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'second_route',
                        "'2'",
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'first_route',
                            "'1'",
                            'second_route',
                            'root_name',
                            true,
                            [],
                            []
                        ),
                        null
                    )
                ),
            ],
            'Singular node' => [
                json_encode([
                    [
                        'definition' => [
                            NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                            'route' => 'second_route',
                            Route::EXPRESSION => "'2'",
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => null,
                    ],
                ], JSON_PRETTY_PRINT),
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'second_route',
                        "'2'",
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    null
                ),
            ],
        ];
    }
}

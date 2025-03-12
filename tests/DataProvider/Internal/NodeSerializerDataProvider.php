<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodeSerializerDataProvider
{
    public static function getSerializesNodesTestScenarios(): array
    {
        return [
            'Tree with root node' => [
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'route_3',
                        'expression_3',
                        null,
                        null,
                        true,
                        [
                            new ParameterDefinition(
                                'parameter_3',
                                false,
                                null
                            ),
                        ],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'route_2',
                            'expression_2',
                            'route_3',
                            'root',
                            true,
                            [],
                            []
                        ),
                        new BreadcrumbNode(
                            new RootBreadcrumbDefinition(
                                null,
                                'root_expression',
                                'root',
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
                            'route' => 'route_3',
                            Route::EXPRESSION => 'expression_3',
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [
                                [
                                    'name' => 'parameter_3',
                                    'isOptional' => false,
                                    'value' => null,
                                ],
                            ],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'route_2',
                                Route::EXPRESSION => 'expression_2',
                                Route::PARENT_ROUTE => 'route_3',
                                Route::ROOT => 'root',
                                Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                                'parameters' => [],
                                'variables' => [],
                            ],
                            'parent' => [
                                'definition' => [
                                    NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROOT,
                                    'route' => null,
                                    Route::EXPRESSION => 'root_expression',
                                    'name' => 'root',
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
                        'route_2',
                        'expression_2',
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'route_1',
                            'expression_1',
                            'route_2',
                            null,
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
                            'route' => 'route_2',
                            Route::EXPRESSION => 'expression_2',
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'route_1',
                                Route::EXPRESSION => 'expression_1',
                                Route::PARENT_ROUTE => 'route_2',
                                Route::ROOT => null,
                                Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                                'parameters' => [],
                                'variables' => [],
                            ],
                            'parent' => null,
                        ],
                    ],
                ], JSON_PRETTY_PRINT),
            ],
            'Standalone node' => [
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'route_1',
                        'expression_1',
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
                            'route' => 'route_1',
                            Route::EXPRESSION => 'expression_1',
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

    public static function getDeserializesNodesTestScenarios(): array
    {
        return [
            'Tree with root node' => [
                json_encode([
                    [
                        'definition' => [
                            NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                            'route' => 'route_3',
                            Route::EXPRESSION => 'expression_3',
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [
                                [
                                    'name' => 'parameter_3',
                                    'isOptional' => true,
                                    'value' => 'value_3',
                                ],
                            ],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'route_2',
                                Route::EXPRESSION => 'expression_2',
                                Route::PARENT_ROUTE => 'route_3',
                                Route::ROOT => 'root',
                                Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                                'parameters' => [],
                                'variables' => [],
                            ],
                            'parent' => [
                                'definition' => [
                                    NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROOT,
                                    'route' => null,
                                    Route::EXPRESSION => 'expression_2',
                                    'name' => 'root',
                                    'variables' => [],
                                ],
                                'parent' => null,
                            ],
                        ],
                    ],
                ], JSON_PRETTY_PRINT),
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'route_3',
                        'expression_3',
                        null,
                        null,
                        true,
                        [
                            new ParameterDefinition(
                                'parameter_3',
                                true,
                                'value_3'
                            ),
                        ],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'route_2',
                            'expression_2',
                            'route_3',
                            'root',
                            true,
                            [],
                            []
                        ),
                        new BreadcrumbNode(
                            new RootBreadcrumbDefinition(
                                null,
                                'expression_2',
                                'root',
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
                            'route' => 'route_2',
                            Route::EXPRESSION => 'expression_2',
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'route_1',
                                Route::EXPRESSION => 'expression_1',
                                Route::PARENT_ROUTE => 'route_2',
                                Route::ROOT => null,
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
                        'route_2',
                        'expression_2',
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'route_1',
                            'expression_1',
                            'route_2',
                            null,
                            true,
                            [],
                            []
                        ),
                        null
                    )
                ),
            ],
            'Standalone node' => [
                json_encode([
                    [
                        'definition' => [
                            NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                            'route' => 'route_1',
                            Route::EXPRESSION => 'expression_1',
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
                        'route_1',
                        'expression_1',
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

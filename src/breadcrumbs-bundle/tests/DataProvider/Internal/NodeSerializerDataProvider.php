<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 * Used UUIDs for tracking purposes - if tests break at any time.
 */
class NodeSerializerDataProvider
{
    public static function getSerializesTestScenarios(): array
    {
        return [
            'Tree with root node' => [
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'route-777997a4-6ebb-4958-b3a1-77db341afebc',
                        'expression-2e3712d7-665b-421a-ad06-46238571722a',
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'route-6cdfac83-7625-45de-a9c3-529f39a48cbd',
                            'expression-0e2fef62-d72c-49c2-8a62-9a75c6da39cd',
                            'route-777997a4-6ebb-4958-b3a1-77db341afebc',
                            'root-e26e0a7c-9645-4368-8720-8029c0449412',
                            true,
                            [],
                            []
                        ),
                        new BreadcrumbNode(
                            new RootBreadcrumbDefinition(
                                null,
                                'expression-a5efd503-e670-4c58-b62e-a63d1e1f535f',
                                'root-e26e0a7c-9645-4368-8720-8029c0449412',
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
                            'route' => 'route-777997a4-6ebb-4958-b3a1-77db341afebc',
                            Route::EXPRESSION => 'expression-2e3712d7-665b-421a-ad06-46238571722a',
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'route-6cdfac83-7625-45de-a9c3-529f39a48cbd',
                                Route::EXPRESSION => 'expression-0e2fef62-d72c-49c2-8a62-9a75c6da39cd',
                                Route::PARENT_ROUTE => 'route-777997a4-6ebb-4958-b3a1-77db341afebc',
                                Route::ROOT => 'root-e26e0a7c-9645-4368-8720-8029c0449412',
                                Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                                'parameters' => [],
                                'variables' => [],
                            ],
                            'parent' => [
                                'definition' => [
                                    NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROOT,
                                    'route' => null,
                                    Route::EXPRESSION => 'expression-a5efd503-e670-4c58-b62e-a63d1e1f535f',
                                    'name' => 'root-e26e0a7c-9645-4368-8720-8029c0449412',
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
                        'route-86239def-77d8-4545-8e2e-edece501c6f7',
                        'expression-343d2ec8-a638-4c68-ab6c-d8b96c47c809',
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'route-f678d968-9613-4138-a344-5b46e1c279a5',
                            'expression-6a9952df-416e-4661-a22a-869acbaebc62',
                            'route-86239def-77d8-4545-8e2e-edece501c6f7',
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
                            'route' => 'route-86239def-77d8-4545-8e2e-edece501c6f7',
                            Route::EXPRESSION => 'expression-343d2ec8-a638-4c68-ab6c-d8b96c47c809',
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'route-f678d968-9613-4138-a344-5b46e1c279a5',
                                Route::EXPRESSION => 'expression-6a9952df-416e-4661-a22a-869acbaebc62',
                                Route::PARENT_ROUTE => 'route-86239def-77d8-4545-8e2e-edece501c6f7',
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
            'Singular node' => [
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'route-c0662791-a9bf-4b88-b38e-b5dbadd430d8',
                        'expression-a7d13061-f0bc-433a-a9e1-5de810de5c85',
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
                            'route' => 'route-c0662791-a9bf-4b88-b38e-b5dbadd430d8',
                            Route::EXPRESSION => 'expression-a7d13061-f0bc-433a-a9e1-5de810de5c85',
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
                            'route' => 'route-d2e6093f-aa22-4f75-9ca8-e3c59335e848',
                            Route::EXPRESSION => 'expression-eba2a968-207b-4753-9a3d-0d3705c9d4df',
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'route-94c1d83a-9fba-4d5c-97cc-3ae6e8861828',
                                Route::EXPRESSION => 'expression-d2a1b2d6-c885-4a74-8872-1226d60b65a2',
                                Route::PARENT_ROUTE => 'route-d2e6093f-aa22-4f75-9ca8-e3c59335e848',
                                Route::ROOT => 'root-c119413f-fad5-465b-bd51-aa95540b0be8',
                                Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                                'parameters' => [],
                                'variables' => [],
                            ],
                            'parent' => [
                                'definition' => [
                                    NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROOT,
                                    'route' => null,
                                    Route::EXPRESSION => 'expression-d2a1b2d6-c885-4a74-8872-1226d60b65a2',
                                    'name' => 'root-c119413f-fad5-465b-bd51-aa95540b0be8',
                                    'variables' => [],
                                ],
                                'parent' => null,
                            ],
                        ],
                    ],
                ], JSON_PRETTY_PRINT),
                new BreadcrumbNode(
                    new RouteBreadcrumbDefinition(
                        'route-d2e6093f-aa22-4f75-9ca8-e3c59335e848',
                        'expression-eba2a968-207b-4753-9a3d-0d3705c9d4df',
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'route-94c1d83a-9fba-4d5c-97cc-3ae6e8861828',
                            'expression-d2a1b2d6-c885-4a74-8872-1226d60b65a2',
                            'route-d2e6093f-aa22-4f75-9ca8-e3c59335e848',
                            'root-c119413f-fad5-465b-bd51-aa95540b0be8',
                            true,
                            [],
                            []
                        ),
                        new BreadcrumbNode(
                            new RootBreadcrumbDefinition(
                                null,
                                'expression-d2a1b2d6-c885-4a74-8872-1226d60b65a2',
                                'root-c119413f-fad5-465b-bd51-aa95540b0be8',
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
                            'route' => 'route-b20a24ca-aae7-4424-bbf1-5015d0dd64e1',
                            Route::EXPRESSION => 'expression-670af5c5-7f77-4b66-9a5a-8b2d2b21d70e',
                            Route::PARENT_ROUTE => null,
                            Route::ROOT => null,
                            Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                            'parameters' => [],
                            'variables' => [],
                        ],
                        'parent' => [
                            'definition' => [
                                NodeSerializer::NODE_TYPE => NodeSerializer::NODE_TYPE_ROUTE,
                                'route' => 'route-603c9d92-b90d-4fbf-aaa5-c9f972c0de95',
                                Route::EXPRESSION => 'expression-065a7212-f41a-4d8f-8834-e0dbfdc3849f',
                                Route::PARENT_ROUTE => 'route-b20a24ca-aae7-4424-bbf1-5015d0dd64e1',
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
                        'route-b20a24ca-aae7-4424-bbf1-5015d0dd64e1',
                        'expression-670af5c5-7f77-4b66-9a5a-8b2d2b21d70e',
                        null,
                        null,
                        true,
                        [],
                        []
                    ),
                    new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'route-603c9d92-b90d-4fbf-aaa5-c9f972c0de95',
                            'expression-065a7212-f41a-4d8f-8834-e0dbfdc3849f',
                            'route-b20a24ca-aae7-4424-bbf1-5015d0dd64e1',
                            null,
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
                            'route' => 'route-ec60eb7a-9366-41e3-9c89-1b0e5ea09f63',
                            Route::EXPRESSION => 'expression-07f95f6f-cce1-4b54-894d-84343f6dd4d1',
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
                        'route-ec60eb7a-9366-41e3-9c89-1b0e5ea09f63',
                        'expression-07f95f6f-cce1-4b54-894d-84343f6dd4d1',
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

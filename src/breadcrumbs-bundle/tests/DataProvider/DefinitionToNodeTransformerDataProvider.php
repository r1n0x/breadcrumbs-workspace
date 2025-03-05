<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider;

use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class DefinitionToNodeTransformerDataProvider
{
    public static function getTransformsTestScenarios(): array
    {
        return [
            'Tree with root node' => [
                ...(function () {
                    $rootDefinition = new RootBreadcrumbDefinition(
                        null,
                        "'Root'",
                        'root_name',
                        []
                    );
                    $secondRouteDefinition = new RouteBreadcrumbDefinition(
                        'second_route',
                        "'2'",
                        null,
                        'root_name',
                        true,
                        [],
                        []
                    );
                    $firstRouteDefinition = new RouteBreadcrumbDefinition(
                        'first_route',
                        "'1'",
                        'second_route',
                        null,
                        true,
                        [],
                        []
                    );
                    $node = new BreadcrumbNode(
                        $firstRouteDefinition,
                        new BreadcrumbNode(
                            $secondRouteDefinition,
                            new BreadcrumbNode(
                                $rootDefinition,
                                null
                            )
                        )
                    );

                    return [
                        $firstRouteDefinition,
                        [
                            $secondRouteDefinition,
                            $firstRouteDefinition,
                            $rootDefinition,
                        ],
                        $node,
                    ];
                })(),
            ],
            'Tree without root node' => [
                ...(function () {
                    $secondRouteDefinition = new RouteBreadcrumbDefinition(
                        'second_route',
                        "'2'",
                        null,
                        null,
                        true,
                        [],
                        []
                    );
                    $firstRouteDefinition = new RouteBreadcrumbDefinition(
                        'first_route',
                        "'1'",
                        'second_route',
                        null,
                        true,
                        [],
                        []
                    );
                    $node = new BreadcrumbNode(
                        $firstRouteDefinition,
                        new BreadcrumbNode(
                            $secondRouteDefinition,
                            null
                        )
                    );

                    return [
                        $firstRouteDefinition,
                        [
                            $secondRouteDefinition,
                            $firstRouteDefinition,
                        ],
                        $node,
                    ];
                })(),
            ],
            'Singular node with unrelated definitions' => [
                ...(function () {
                    $secondRouteDefinition = new RouteBreadcrumbDefinition(
                        'second_route',
                        "'2'",
                        null,
                        null,
                        true,
                        [],
                        []
                    );
                    $firstRouteDefinition = new RouteBreadcrumbDefinition(
                        'first_route',
                        "'1'",
                        null,
                        null,
                        true,
                        [],
                        []
                    );
                    $rootDefinition = new RootBreadcrumbDefinition(
                        null,
                        "'Root'",
                        'root_name',
                        []
                    );
                    $node = new BreadcrumbNode(
                        $firstRouteDefinition,
                        null
                    );

                    return [
                        $firstRouteDefinition,
                        [
                            $secondRouteDefinition,
                            $firstRouteDefinition,
                            $rootDefinition,
                        ],
                        $node,
                    ];
                })(),
            ],
            'Singular node' => [
                ...(function () {
                    $definition = new RouteBreadcrumbDefinition(
                        'second_route',
                        "'2'",
                        null,
                        null,
                        true,
                        [],
                        []
                    );
                    $definitions = [$definition];
                    $node = new BreadcrumbNode(
                        $definition,
                        null
                    );

                    return [
                        $definition,
                        $definitions,
                        $node,
                    ];
                })(),
            ],
        ];
    }
}

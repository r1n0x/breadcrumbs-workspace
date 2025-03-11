<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal;

use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class DefinitionToNodeTransformerDataProvider
{
    public static function getTransformsDefinitionToNodeTestScenarios(): array
    {
        return [
            'Tree with root node' => (function () {
                $root = new RootBreadcrumbDefinition(
                    Dummy::string(),
                    Dummy::string(),
                    'root',
                    []
                );

                $secondRoute = new RouteBreadcrumbDefinition(
                    'route_2',
                    Dummy::string(),
                    null,
                    'root',
                    Dummy::bool(),
                    [],
                    []
                );

                $firstRoute = new RouteBreadcrumbDefinition(
                    Dummy::string(),
                    Dummy::string(),
                    'route_2',
                    null,
                    Dummy::bool(),
                    [],
                    []
                );

                return [
                    $firstRoute,
                    [
                        $secondRoute,
                        $firstRoute,
                        $root,
                    ],
                    new BreadcrumbNode(
                        $firstRoute,
                        new BreadcrumbNode(
                            $secondRoute,
                            new BreadcrumbNode(
                                $root,
                                null
                            )
                        )
                    ),
                ];
            })(),
            'Tree without root node' => (function () {
                $secondRoute = new RouteBreadcrumbDefinition(
                    'route_2',
                    Dummy::string(),
                    null,
                    null,
                    Dummy::bool(),
                    [],
                    []
                );

                $firstRoute = new RouteBreadcrumbDefinition(
                    Dummy::string(),
                    Dummy::string(),
                    'route_2',
                    null,
                    Dummy::bool(),
                    [],
                    []
                );

                return [
                    $firstRoute,
                    [
                        $secondRoute,
                        $firstRoute,
                    ],
                    new BreadcrumbNode(
                        $firstRoute,
                        new BreadcrumbNode(
                            $secondRoute,
                            null
                        )
                    ),
                ];
            })(),
            'Standalone node with multiple unrelated definitions' => (function () {
                $secondRoute = new RouteBreadcrumbDefinition(
                    'route_2',
                    Dummy::string(),
                    null,
                    null,
                    Dummy::bool(),
                    [],
                    []
                );

                $firstRoute = new RouteBreadcrumbDefinition(
                    'route_1',
                    Dummy::string(),
                    null,
                    null,
                    Dummy::bool(),
                    [],
                    []
                );

                $root = new RootBreadcrumbDefinition(
                    Dummy::string(),
                    Dummy::string(),
                    'root',
                    []
                );

                return [
                    $firstRoute,
                    [
                        $secondRoute,
                        $firstRoute,
                        $root,
                    ],
                    new BreadcrumbNode(
                        $firstRoute,
                        null
                    ),
                ];
            })(),
            'Standalone node' => (function () {
                $route = new RouteBreadcrumbDefinition(
                    'route',
                    Dummy::string(),
                    null,
                    null,
                    Dummy::bool(),
                    [],
                    []
                );

                return [
                    $route,
                    [
                        $route,
                    ],
                    new BreadcrumbNode(
                        $route,
                        null
                    ),
                ];
            })(),
        ];
    }
}

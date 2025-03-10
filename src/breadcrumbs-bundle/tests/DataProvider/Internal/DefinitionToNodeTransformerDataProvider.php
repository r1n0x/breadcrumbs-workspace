<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal;

use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

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
                    Unused::string(),
                    Unused::string(),
                    'root-749f6abe-58f0-46a1-a8a5-7035417681d6',
                    []
                );

                $secondRoute = new RouteBreadcrumbDefinition(
                    'route-58ed6f71-25d2-458f-8fdd-85d23064bb74',
                    Unused::string(),
                    null,
                    'root-749f6abe-58f0-46a1-a8a5-7035417681d6',
                    Unused::bool(),
                    [],
                    []
                );

                $firstRoute = new RouteBreadcrumbDefinition(
                    Unused::string(),
                    Unused::string(),
                    'route-58ed6f71-25d2-458f-8fdd-85d23064bb74',
                    null,
                    Unused::bool(),
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
                    'route-80a38497-c618-440e-bf10-5bb3bf74a551',
                    Unused::string(),
                    null,
                    null,
                    Unused::bool(),
                    [],
                    []
                );

                $firstRoute = new RouteBreadcrumbDefinition(
                    Unused::string(),
                    Unused::string(),
                    'route-80a38497-c618-440e-bf10-5bb3bf74a551',
                    null,
                    Unused::bool(),
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
                    'route-15a83aaa-a0e9-47fc-81de-90c8dc85d2cc',
                    Unused::string(),
                    null,
                    null,
                    Unused::bool(),
                    [],
                    []
                );

                $firstRoute = new RouteBreadcrumbDefinition(
                    'route-a47794b6-dbe3-4e48-be27-63ae5622ee3b',
                    Unused::string(),
                    null,
                    null,
                    Unused::bool(),
                    [],
                    []
                );

                $root = new RootBreadcrumbDefinition(
                    Unused::string(),
                    Unused::string(),
                    'root-51f7390d-ed7e-426a-83fc-7a18b0f7817f',
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
                    'route-2b245794-c390-4a17-be56-c3caf65cb564',
                    Unused::string(),
                    null,
                    null,
                    Unused::bool(),
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

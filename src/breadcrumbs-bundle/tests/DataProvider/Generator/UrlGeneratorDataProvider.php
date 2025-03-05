<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Generator;

use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouterStub;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class UrlGeneratorDataProvider
{
    public static function getGeneratesTestScenarios(): array
    {
        return [
            'Root definition without route name' => [
                function () {
                    $definition = new RootBreadcrumbDefinition(
                        null,
                        "'Root'",
                        'root_name',
                        []
                    );

                    return [
                        $definition,
                        null,
                    ];
                },
            ],
            'Root definition with route name' => [
                function (RouterStub $router) {
                    $definition = new RootBreadcrumbDefinition(
                        'root',
                        "'Root'",
                        'root_name',
                        []
                    );
                    $router
                        ->addRouteStub('root', '/root/admin')
                    ;

                    return [
                        $definition,
                        '/root/admin',
                    ];
                },
            ],
            'Route definition with parameters' => [
                function (RouterStub $router, ParametersHolder $holder) {
                    $definition = new RouteBreadcrumbDefinition(
                        'animal_details',
                        "''",
                        null,
                        null,
                        true,
                        [
                            'name',
                        ]
                    );
                    $holder
                        ->set(new Parameter('name', 'duck'))
                    ;
                    $router
                        ->addRouteStub('animal_details', '/animal/{name}')
                    ;

                    return [
                        $definition,
                        '/animal/duck',
                    ];
                },
            ],
        ];
    }
}

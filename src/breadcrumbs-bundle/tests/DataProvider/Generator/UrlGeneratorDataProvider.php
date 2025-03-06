<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Generator;

use R1n0x\BreadcrumbsBundle\Context;
use R1n0x\BreadcrumbsBundle\EventListener\ControllerArgumentsListener;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouterStub;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouteStub;

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
                        ->addRouteStub(RouteStub::create('root', '/root/admin'))
                    ;

                    return [
                        $definition,
                        '/root/admin',
                    ];
                },
            ],
            'Route definition with parameters' => [
                function (RouterStub $router, Context $context) {
                    $definition = new RouteBreadcrumbDefinition(
                        'animal_details',
                        "''",
                        null,
                        null,
                        true,
                        [
                            new ParameterDefinition(
                                'name',
                                false,
                                null
                            ),
                        ]
                    );
                    $context->getParametersHolder()
                        ->set(new Parameter('name', 'duck'))
                    ;

                    $router
                        ->addRouteStub(RouteStub::create('animal_details', '/animal/{name}'))
                    ;

                    return [
                        $definition,
                        '/animal/duck',
                    ];
                },
            ],
            'Route definition with default parameters' => [
                function (RouterStub $router, Context $context) {
                    $definition = new RouteBreadcrumbDefinition(
                        'animal_details',
                        "''",
                        null,
                        null,
                        true,
                        [
                            new ParameterDefinition(
                                'name',
                                true,
                                null
                            ),
                        ]
                    );

                    /*
                     * this will be set by symfony framework at runtime {@see ControllerArgumentsListener}
                     */
                    $context->getParametersHolder()
                        ->set(new Parameter('name', null))
                    ;

                    $router
                        ->addRouteStub(RouteStub::create('animal_details', '/animal/{name}'))
                    ;

                    return [
                        $definition,
                        '/animal/',
                    ];
                },
            ],
        ];
    }
}

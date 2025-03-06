<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Generator;

use R1n0x\BreadcrumbsBundle\Context;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\Provider\RouteProvider;
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
                        "'static-value-6d9adf11-470a-438d-a36c-d6f8f2656fad'",
                        'root_name-94bb3a28-8cd8-443b-a705-a8bc6961599c',
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
                        'root-ccb35d2f-b3f8-4217-8876-776c8af84773',
                        "'static-value-22c69e0c-3008-4463-8879-476599389234'",
                        'root_name-94bb3a28-8cd8-443b-a705-a8bc6961599c',
                        []
                    );

                    $router
                        ->addRouteStub(RouteProvider::provide('root-ccb35d2f-b3f8-4217-8876-776c8af84773', '/root/admin'));

                    return [
                        $definition,
                        '/root/admin',
                    ];
                },
            ],
            'Route definition with parameters' => [
                function (RouterStub $router, Context $context) {
                    $definition = new RouteBreadcrumbDefinition(
                        'animal_details-db9f8db7-728f-44c7-a19e-d408242e6f3e',
                        "'static-value-8b41bd87-80fe-424c-aa2d-bfd2cf85a50a'",
                        null,
                        null,
                        true,
                        [
                            new ParameterDefinition(
                                'animal_name-b93a871d-55e7-4813-93bf-15e0772311dc',
                                false,
                                null
                            ),
                        ]
                    );

                    $context->setParameter('animal_name-b93a871d-55e7-4813-93bf-15e0772311dc', 'duck');

                    $router
                        ->addRouteStub(RouteProvider::provide(
                            'animal_details-db9f8db7-728f-44c7-a19e-d408242e6f3e',
                            '/animal/{animal_name-b93a871d-55e7-4813-93bf-15e0772311dc}'
                        ));

                    return [
                        $definition,
                        '/animal/duck',
                    ];
                },
            ],
            'Route definition with optional parameters' => [
                function (RouterStub $router, Context $context) {
                    $definition = new RouteBreadcrumbDefinition(
                        'animal_details-41ddb257-5f31-4730-b06a-1d2d4a844fa5',
                        "'static-value-68c9a4f0-7eb9-4e76-9962-3e60a9df0ee1'",
                        null,
                        null,
                        true,
                        [
                            new ParameterDefinition(
                                'animal_name-dd12e1c3-774b-423d-9f40-0fc548e3a83e',
                                true,
                                null
                            ),
                        ]
                    );

                    /*
                     * this will be set by symfony framework at runtime {@see ControllerArgumentsListener}
                     */
                    $context->setParameter('animal_name-dd12e1c3-774b-423d-9f40-0fc548e3a83e', null);

                    $router
                        ->addRouteStub(RouteProvider::provide(
                            'animal_details-41ddb257-5f31-4730-b06a-1d2d4a844fa5',
                            '/animal/{animal_name-dd12e1c3-774b-423d-9f40-0fc548e3a83e}'
                        ));

                    return [
                        $definition,
                        '/animal/',
                    ];
                },
            ],
        ];
    }
}

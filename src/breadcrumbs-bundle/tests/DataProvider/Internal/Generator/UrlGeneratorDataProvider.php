<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Generator;

use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ContextProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\RouteProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\Unused;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouterStub;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 * Used UUIDs for tracking purposes - if tests break at any time.
 */
class UrlGeneratorDataProvider
{
    public static function getGeneratesTestScenarios(): array
    {
        return [
            'Root definition without route name' => [
                ContextProvider::provide(),
                self::createRouterStub(),
                new RootBreadcrumbDefinition(
                    null,
                    Unused::string(),
                    Unused::string(),
                    []
                ),
                null,
            ],
            'Root definition with route name' => [
                ...(function () {
                    $router = self::createRouterStub()
                        ->addRouteStub(RouteProvider::provide('root-ccb35d2f-b3f8-4217-8876-776c8af84773', '/root/admin'));

                    return [
                        ContextProvider::provide(),
                        $router,
                        new RootBreadcrumbDefinition(
                            'root-ccb35d2f-b3f8-4217-8876-776c8af84773',
                            Unused::string(),
                            Unused::string(),
                            []
                        ),
                        '/root/admin',
                    ];
                })(),
            ],
            'Route definition with global parameters' => [
                ...(function () {
                    $context = ContextProvider::provide()
                        ->setParameter('parameter-7469179f-b8a5-45a7-b676-66796e2e156a', 'duck');

                    $router = self::createRouterStub()
                        ->addRouteStub(RouteProvider::provide(
                            'route-cbeb8187-435c-410c-a974-94622921d691',
                            '/animal/{parameter-7469179f-b8a5-45a7-b676-66796e2e156a}'
                        ));

                    return [
                        $context,
                        $router,
                        new RouteBreadcrumbDefinition(
                            'route-cbeb8187-435c-410c-a974-94622921d691',
                            Unused::string(),
                            null,
                            null,
                            Unused::bool(),
                            [
                                new ParameterDefinition(
                                    'parameter-7469179f-b8a5-45a7-b676-66796e2e156a',
                                    false,
                                    null
                                ),
                            ]
                        ),
                        '/animal/duck',
                    ];
                })(),
            ],
            'Route definition with scoped parameters' => [
                ...(function () {
                    $context = ContextProvider::provide()
                        ->setParameter(
                            'parameter-31b8dcbc-9df9-4cef-a420-6bea4b4a1e1d',
                            'owl',
                            'route-58cbb011-b2b0-4457-b204-5ae060f4c1d3'
                        );

                    $router = self::createRouterStub()
                        ->addRouteStub(RouteProvider::provide(
                            'route-58cbb011-b2b0-4457-b204-5ae060f4c1d3',
                            '/animal/{parameter-31b8dcbc-9df9-4cef-a420-6bea4b4a1e1d}'
                        ));

                    return [
                        $context,
                        $router,
                        new RouteBreadcrumbDefinition(
                            'route-58cbb011-b2b0-4457-b204-5ae060f4c1d3',
                            Unused::string(),
                            null,
                            null,
                            Unused::bool(),
                            [
                                new ParameterDefinition(
                                    'parameter-31b8dcbc-9df9-4cef-a420-6bea4b4a1e1d',
                                    false,
                                    null
                                ),
                            ]
                        ),
                        '/animal/owl',
                    ];
                })(),
            ],
            'Prioritizes route parameters above global parameters' => [
                ...(function () {
                    $context = ContextProvider::provide()
                        ->setParameter(
                            'parameter-8cf18f39-e7cc-40ad-a86d-e805c7b1020a',
                            'wolf',
                        )
                        ->setParameter(
                            'parameter-8cf18f39-e7cc-40ad-a86d-e805c7b1020a',
                            'deer',
                            'route-e6a60f0c-ecf8-4d0e-a1c1-1f20e917becd'
                        );

                    $router = self::createRouterStub()
                        ->addRouteStub(RouteProvider::provide(
                            'route-e6a60f0c-ecf8-4d0e-a1c1-1f20e917becd',
                            '/animal/{parameter-8cf18f39-e7cc-40ad-a86d-e805c7b1020a}'
                        ));

                    return [
                        $context,
                        $router,
                        new RouteBreadcrumbDefinition(
                            'route-e6a60f0c-ecf8-4d0e-a1c1-1f20e917becd',
                            Unused::string(),
                            null,
                            null,
                            Unused::bool(),
                            [
                                new ParameterDefinition(
                                    'parameter-8cf18f39-e7cc-40ad-a86d-e805c7b1020a',
                                    false,
                                    null
                                ),
                            ]
                        ),
                        '/animal/deer',
                    ];
                })(),
            ],
            'Route definition with optional parameters' => [
                ...(function () {
                    /*
                     * this will be set by symfony framework at runtime {@see ControllerArgumentsListener}
                     */
                    $context = ContextProvider::provide()
                        ->setParameter('parameter-aa9e68e8-6826-4fc1-9c31-c8199a4ce291', null);

                    $router = self::createRouterStub()
                        ->addRouteStub(RouteProvider::provide(
                            'route-752ab06d-0457-4fde-83b8-9c2240069364',
                            '/animal/{parameter-aa9e68e8-6826-4fc1-9c31-c8199a4ce291}'
                        ));

                    return [
                        $context,
                        $router,
                        new RouteBreadcrumbDefinition(
                            'route-752ab06d-0457-4fde-83b8-9c2240069364',
                            Unused::string(),
                            Unused::string(),
                            Unused::string(),
                            Unused::bool(),
                            [
                                new ParameterDefinition(
                                    'parameter-aa9e68e8-6826-4fc1-9c31-c8199a4ce291',
                                    true,
                                    null
                                ),
                            ]
                        ),
                        '/animal/',
                    ];
                })(),
            ],
        ];
    }

    private static function createRouterStub(): RouterStub
    {
        return new RouterStub();
    }
}

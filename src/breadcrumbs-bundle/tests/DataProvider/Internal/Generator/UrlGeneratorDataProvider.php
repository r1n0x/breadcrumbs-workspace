<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Generator;

use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\UrlParametersProviderFake;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouterStub;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class UrlGeneratorDataProvider
{
    public static function getGeneratesUrlTestScenarios(): array
    {
        return [
            'Root without route name' => [
                UrlParametersProviderFake::createWithParameters(),
                RouterStub::create(),
                new RootBreadcrumbDefinition(
                    null,
                    Dummy::string(),
                    Dummy::string(),
                    []
                ),
                null,
            ],
            'Root with route name' => [
                UrlParametersProviderFake::createWithParameters(),
                RouterStub::create()
                    ->addRouteStub('root-ccb35d2f-b3f8-4217-8876-776c8af84773', '/root/admin'),
                new RootBreadcrumbDefinition(
                    'root-ccb35d2f-b3f8-4217-8876-776c8af84773',
                    Dummy::string(),
                    Dummy::string(),
                    []
                ),
                '/root/admin',
            ],
            'Route with global parameters' => [
                UrlParametersProviderFake::createWithParameters([
                    new Parameter(
                        'parameter-7469179f-b8a5-45a7-b676-66796e2e156a',
                        null,
                        'duck',
                        Dummy::null()
                    ),
                ]),
                RouterStub::create()
                    ->addRouteStub('route-cbeb8187-435c-410c-a974-94622921d691', '/animal/{parameter-7469179f-b8a5-45a7-b676-66796e2e156a}'),
                new RouteBreadcrumbDefinition(
                    'route-cbeb8187-435c-410c-a974-94622921d691',
                    Dummy::string(),
                    null,
                    null,
                    Dummy::bool(),
                    [
                        new ParameterDefinition(
                            'parameter-7469179f-b8a5-45a7-b676-66796e2e156a',
                            false,
                            null
                        ),
                    ]
                ),
                '/animal/duck',
            ],
            'Route with scoped parameters' => [
                UrlParametersProviderFake::createWithParameters([
                    new Parameter(
                        'parameter-31b8dcbc-9df9-4cef-a420-6bea4b4a1e1d',
                        'route-58cbb011-b2b0-4457-b204-5ae060f4c1d3',
                        'owl',
                        Dummy::null()
                    ),
                ]),
                RouterStub::create()
                    ->addRouteStub('route-58cbb011-b2b0-4457-b204-5ae060f4c1d3', '/animal/{parameter-31b8dcbc-9df9-4cef-a420-6bea4b4a1e1d}'),
                new RouteBreadcrumbDefinition(
                    'route-58cbb011-b2b0-4457-b204-5ae060f4c1d3',
                    Dummy::string(),
                    null,
                    null,
                    Dummy::bool(),
                    [
                        new ParameterDefinition(
                            'parameter-31b8dcbc-9df9-4cef-a420-6bea4b4a1e1d',
                            false,
                            null
                        ),
                    ]
                ),
                '/animal/owl',
            ],
            'Route with optional parameters' => [
                UrlParametersProviderFake::createWithParameters([
                    new Parameter(
                        'parameter-aa9e68e8-6826-4fc1-9c31-c8199a4ce291',
                        null,
                        null,
                        Dummy::null()
                    ),
                ]),
                RouterStub::create()
                    ->addRouteStub('route-752ab06d-0457-4fde-83b8-9c2240069364', '/animal/{parameter-aa9e68e8-6826-4fc1-9c31-c8199a4ce291}'),
                new RouteBreadcrumbDefinition(
                    'route-752ab06d-0457-4fde-83b8-9c2240069364',
                    Dummy::string(),
                    Dummy::string(),
                    Dummy::string(),
                    Dummy::bool(),
                    [
                        new ParameterDefinition(
                            'parameter-aa9e68e8-6826-4fc1-9c31-c8199a4ce291',
                            true,
                            null
                        ),
                    ]
                ),
                '/animal/',
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Validator\Node;

use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ContextParameterProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ContextVariableProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodeContextValidatorDataProvider
{
    // 63 felt like good number, overkill but hey
    private const int NEST_LEVEL = 63;
    private const string ROUTE_NONE = 'ROUTE_NONE';

    public static function getThrowsExceptionTestScenarios(): array
    {
        return [
            'Undefined value of parameter on route node' => (function () {
                $parameterProvider = ContextParameterProviderProvider::empty();

                return [
                    self::createNested(self::createRoute([
                        new ParameterDefinition(
                            'parameter-e7340724-46de-4a72-93c0-eb3c32e7f4e1',
                            false,
                            null
                        ),
                    ], [])),
                    ContextVariableProviderProvider::createWithVariables([], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
            'Undefined value of variable on route node' => (function () {
                $node = self::createNested(self::createRoute([], [
                    'variable-43e824ca-76fc-4d9b-bf71-84ee7ad7a6e2',
                ]));

                $parameterProvider = ContextParameterProviderProvider::empty();

                return [
                    $node,
                    ContextVariableProviderProvider::createWithVariables([], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
            'Undefined value of variable on root node' => (function () {
                $node = self::createNested(self::createRoot([
                    'variable-84b9324b-657e-4b9c-adef-f97595774043',
                ]));

                $parameterProvider = ContextParameterProviderProvider::empty();

                return [
                    $node,
                    ContextVariableProviderProvider::createWithVariables([], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
        ];
    }

    public static function getValidatesContextTestScenarios(): array
    {
        return [
            'Parameter on route node' => (function () {
                $node = self::createNested(self::createRoute([
                    new ParameterDefinition(
                        'parameter-2adacdf1-b453-4839-8665-f329b2f82537',
                        false,
                        null
                    ),
                ], []));

                $parameterProvider = ContextParameterProviderProvider::createWithParameters([
                    new Parameter(
                        'parameter-2adacdf1-b453-4839-8665-f329b2f82537',
                        null,
                        'value-0538095b-0090-44b4-b791-382796ecb37d',
                        Unused::null()
                    ),
                ]);

                return [
                    $node,
                    ContextVariableProviderProvider::createWithVariables([], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
            'Parameter with optional value on route node' => (function () {
                $node = self::createNested(self::createRoute([
                    new ParameterDefinition(
                        'parameter-1ddb50f3-6539-4d70-8a11-55923f3861c4',
                        true,
                        'value-e803f66b-1886-493e-9713-f509cb8b8eb2'
                    ),
                ], []));

                $parameterProvider = ContextParameterProviderProvider::empty();

                return [
                    $node,
                    ContextVariableProviderProvider::createWithVariables([], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
            'Variable on route node' => (function () {
                $node = self::createNested(self::createRoute([], [
                    'variable-2aceadf3-820a-42eb-bf37-16e68d4f8596',
                ], 'route-149d083c-25c9-48a4-8b04-0bcc72dc973c'));

                $parameterProvider = ContextParameterProviderProvider::empty();

                return [
                    $node,
                    ContextVariableProviderProvider::createWithVariables([
                        new Variable(
                            'variable-2aceadf3-820a-42eb-bf37-16e68d4f8596',
                            'value-2f817683-3089-414b-b00b-c31460c739a3',
                            'route-149d083c-25c9-48a4-8b04-0bcc72dc973c'
                        ),
                    ], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
            'Variable on root node' => (function () {
                $node = self::createNested(self::createRoot([
                    'variable-f45cef06-9829-4cc7-bb5c-bbf8b2ed0036',
                ], self::ROUTE_NONE));

                $parameterProvider = ContextParameterProviderProvider::empty();

                return [
                    $node,
                    ContextVariableProviderProvider::createWithVariables([
                        new Variable(
                            'variable-f45cef06-9829-4cc7-bb5c-bbf8b2ed0036',
                            'value-a69056e5-7368-4e2b-a56b-4c1f0bd03b5c'
                        ),
                    ], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
        ];
    }

    private static function createNested(BreadcrumbDefinition $definition): BreadcrumbNode
    {
        return self::_createWithDepth(self::NEST_LEVEL, $definition, 1);
    }

    private static function _createWithDepth(int $depth, BreadcrumbDefinition $definition, int $level): BreadcrumbNode
    {
        if ($depth <= $level) {
            return new BreadcrumbNode(
                $definition,
                null
            );
        }

        return new BreadcrumbNode(
            self::createRoute([], []),
            self::_createWithDepth($depth, $definition, ++$level)
        );
    }

    /**
     * @param array<int, ParameterDefinition> $parameters
     * @param array<int, string> $variables
     */
    private static function createRoute(array $parameters, array $variables, ?string $routeName = null): RouteBreadcrumbDefinition
    {
        return new RouteBreadcrumbDefinition(
            $routeName ?? Unused::string(),
            Unused::string(),
            Unused::string(),
            Unused::string(),
            Unused::bool(),
            $parameters,
            $variables
        );
    }

    /**
     * @param array<int, string> $variables
     */
    private static function createRoot(array $variables, ?string $routeName = null): RootBreadcrumbDefinition
    {
        return new RootBreadcrumbDefinition(
            self::ROUTE_NONE === $routeName ? null : Unused::string(),
            Unused::string(),
            Unused::string(),
            $variables
        );
    }
}

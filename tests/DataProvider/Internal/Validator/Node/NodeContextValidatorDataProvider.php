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
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\ContextParameterProviderFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\ContextVariableProviderFake;

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
                $parameterProvider = ContextParameterProviderFake::createWithParameters();

                return [
                    self::createNested(self::createRoute([
                        new ParameterDefinition(
                            Dummy::string(),
                            false,
                            null
                        ),
                    ], [])),
                    ContextVariableProviderFake::createWithVariables([], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
            'Undefined value of variable on route node' => (function () {
                $node = self::createNested(self::createRoute([], [
                    Dummy::string(),
                ]));

                $parameterProvider = ContextParameterProviderFake::createWithParameters();

                return [
                    $node,
                    ContextVariableProviderFake::createWithVariables([], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
            'Undefined value of variable on root node' => (function () {
                $node = self::createNested(self::createRoot([
                    Dummy::string(),
                ]));

                $parameterProvider = ContextParameterProviderFake::createWithParameters();

                return [
                    $node,
                    ContextVariableProviderFake::createWithVariables([], $parameterProvider),
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
                        'parameter',
                        false,
                        null
                    ),
                ], []));

                $parameterProvider = ContextParameterProviderFake::createWithParameters([
                    new Parameter(
                        'parameter',
                        null,
                        Dummy::string(),
                        Dummy::null()
                    ),
                ]);

                return [
                    $node,
                    ContextVariableProviderFake::createWithVariables([], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
            'Parameter with optional value on route node' => (function () {
                $node = self::createNested(self::createRoute([
                    new ParameterDefinition(
                        Dummy::string(),
                        true,
                        Dummy::string()
                    ),
                ], []));

                $parameterProvider = ContextParameterProviderFake::createWithParameters();

                return [
                    $node,
                    ContextVariableProviderFake::createWithVariables([], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
            'Variable on route node' => (function () {
                $node = self::createNested(self::createRoute([], [
                    'variable',
                ], 'route'));

                $parameterProvider = ContextParameterProviderFake::createWithParameters();

                return [
                    $node,
                    ContextVariableProviderFake::createWithVariables([
                        new Variable(
                            'variable',
                            Dummy::string(),
                            'route'
                        ),
                    ], $parameterProvider),
                    $parameterProvider,
                ];
            })(),
            'Variable on root node' => (function () {
                $node = self::createNested(self::createRoot([
                    'variable',
                ], self::ROUTE_NONE));

                $parameterProvider = ContextParameterProviderFake::createWithParameters();

                return [
                    $node,
                    ContextVariableProviderFake::createWithVariables([
                        new Variable(
                            'variable',
                            Dummy::string()
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
            $routeName ?? Dummy::string(),
            Dummy::string(),
            Dummy::string(),
            Dummy::string(),
            Dummy::bool(),
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
            self::ROUTE_NONE === $routeName ? null : Dummy::string(),
            Dummy::string(),
            Dummy::string(),
            $variables
        );
    }
}

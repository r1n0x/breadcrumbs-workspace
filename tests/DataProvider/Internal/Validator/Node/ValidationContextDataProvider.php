<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Validator\Node;

use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\ErrorType;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RootError;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RouteError;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\ValidationContext;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ValidationContextDataProvider
{
    public static function getContainsErrorsTestScenarios(): array
    {
        return [
            'Invalid route parameters' => [
                ...(function () {
                    return [
                        function (ValidationContext $context) {
                            $context
                                ->addRouteParameterViolation(
                                    'route_1',
                                    'parameter_1'
                                );
                            $context
                                ->addRouteParameterViolation(
                                    'route_1',
                                    'parameter_2'
                                );
                            $context
                                ->addRouteParameterViolation(
                                    'route_2',
                                    'parameter_3'
                                );
                        },
                        2,
                        2,
                        0,
                        [
                            self::createRouteError(
                                'route_1',
                                ErrorType::Parameter,
                                [
                                    'parameter_1',
                                    'parameter_2',
                                ]
                            ),
                            self::createRouteError(
                                'route_2',
                                ErrorType::Parameter,
                                [
                                    'parameter_3',
                                ]
                            ),
                        ],
                    ];
                })(),
            ],
            'Invalid route variables' => [
                ...(function () {
                    return [
                        function (ValidationContext $context) {
                            $context
                                ->addRouteVariableViolation(
                                    'route_1',
                                    'variable_1'
                                );
                            $context
                                ->addRouteVariableViolation(
                                    'route_1',
                                    'variable_2'
                                );
                            $context
                                ->addRouteVariableViolation(
                                    'route_2',
                                    'variable_3'
                                );
                        },
                        2,
                        2,
                        0,
                        [
                            self::createRouteError(
                                'route_1',
                                ErrorType::Variable,
                                [],
                                [
                                    'variable_1',
                                    'variable_2',
                                ]
                            ),
                            self::createRouteError(
                                'route_2',
                                ErrorType::Variable,
                                [],
                                [
                                    'variable_3',
                                ]
                            ),
                        ],
                    ];
                })(),
            ],
            'Invalid root variables' => [
                ...(function () {
                    return [
                        function (ValidationContext $context) {
                            $context
                                ->addRootVariableViolation(
                                    'root_1',
                                    'variable_1'
                                );
                            $context
                                ->addRootVariableViolation(
                                    'root_1',
                                    'variable_2'
                                );
                            $context
                                ->addRootVariableViolation(
                                    'root_2',
                                    'variable_3'
                                );
                        },
                        2,
                        0,
                        2,
                        [
                            self::createRootError(
                                'root_1',
                                [
                                    'variable_1',
                                    'variable_2',
                                ]
                            ),
                            self::createRootError(
                                'root_2',
                                [
                                    'variable_3',
                                ]
                            ),
                        ],
                    ];
                })(),
            ],
            'Invalid route and root' => [
                ...(function () {
                    return [
                        function (ValidationContext $context) {
                            $context
                                ->addRootVariableViolation(
                                    'root_1',
                                    'variable_1'
                                );
                            $context
                                ->addRootVariableViolation(
                                    'root_1',
                                    'variable_2'
                                );
                            $context
                                ->addRouteVariableViolation(
                                    'route_1',
                                    'variable_1'
                                );
                            $context
                                ->addRouteParameterViolation(
                                    'route_1',
                                    'parameter_1'
                                );
                            $context
                                ->addRouteParameterViolation(
                                    'route_1',
                                    'parameter_2'
                                );
                            $context
                                ->addRouteVariableViolation(
                                    'route_1',
                                    'variable_2'
                                );
                            $context
                                ->addRouteParameterViolation(
                                    'route_3',
                                    'parameter_3'
                                );
                        },
                        4,
                        3,
                        1,
                        [
                            self::createRouteError(
                                'route_1',
                                ErrorType::Variable,
                                [],
                                [
                                    'variable_1',
                                    'variable_2',
                                ]
                            ),
                            self::createRouteError(
                                'route_1',
                                ErrorType::Parameter,
                                [
                                    'parameter_1',
                                    'parameter_2',
                                ],
                            ),
                            self::createRouteError(
                                'route_3',
                                ErrorType::Parameter,
                                [
                                    'parameter_3',
                                ],
                            ),
                            self::createRootError(
                                'root_1',
                                [
                                    'variable_1',
                                    'variable_2',
                                ]
                            ),
                        ],
                    ];
                })(),
            ],
        ];
    }

    private static function createRouteError(
        string $routeName,
        ErrorType $type,
        array $parameters = [],
        array $variables = []
    ): RouteError {
        $error = new RouteError(
            $type,
            $routeName,
        );
        foreach (array_merge($parameters, $variables) as $name) {
            $error->addName($name);
        }

        return $error;
    }

    private static function createRootError(
        string $routeName,
        array $variables = []
    ): RootError {
        $error = new RootError(
            ErrorType::Variable,
            $routeName,
        );
        foreach ($variables as $name) {
            $error->addName($name);
        }

        return $error;
    }
}

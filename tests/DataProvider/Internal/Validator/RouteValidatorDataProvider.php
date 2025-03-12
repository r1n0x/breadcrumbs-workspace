<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Validator;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteValidatorDataProvider
{
    public static function getThrowsExceptionTestScenarios(): array
    {
        return [
            'Invalid parent route (number)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => Dummy::integer(),
                ]),
                RouteValidator::ERROR_CODE_PARENT_ROUTE_STRING,
            ],
            'Invalid parent route (array)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => Dummy::array(),
                ]),
                RouteValidator::ERROR_CODE_PARENT_ROUTE_STRING,
            ],
            'Invalid parent route (bool)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => Dummy::bool(),
                ]),
                RouteValidator::ERROR_CODE_PARENT_ROUTE_STRING,
            ],
            'Invalid expression (number)' => [
                new Route(breadcrumb: [
                    Route::EXPRESSION => Dummy::integer(),
                ]),
                RouteValidator::ERROR_CODE_EXPRESSION_STRING,
            ],
            'Invalid expression (array)' => [
                new Route(breadcrumb: [
                    Route::EXPRESSION => Dummy::array(),
                ]),
                RouteValidator::ERROR_CODE_EXPRESSION_STRING,
            ],
            'Invalid expression (bool)' => [
                new Route(breadcrumb: [
                    Route::EXPRESSION => Dummy::bool(),
                ]),
                RouteValidator::ERROR_CODE_EXPRESSION_STRING,
            ],
            'Invalid pass parameters to expression (integer)' => [
                new Route(breadcrumb: [
                    Route::PASS_PARAMETERS_TO_EXPRESSION => Dummy::integer(),
                ]),
                RouteValidator::ERROR_CODE_PASS_PARAMETERS_TO_EXPRESSION_BOOL,
            ],
            'Invalid pass parameters to expression (array)' => [
                new Route(breadcrumb: [
                    Route::PASS_PARAMETERS_TO_EXPRESSION => Dummy::array(),
                ]),
                RouteValidator::ERROR_CODE_PASS_PARAMETERS_TO_EXPRESSION_BOOL,
            ],
            'Invalid pass parameters to expression (string)' => [
                new Route(breadcrumb: [
                    Route::PASS_PARAMETERS_TO_EXPRESSION => Dummy::string(),
                ]),
                RouteValidator::ERROR_CODE_PASS_PARAMETERS_TO_EXPRESSION_BOOL,
            ],
            'Invalid root (integer)' => [
                new Route(breadcrumb: [
                    Route::ROOT => Dummy::integer(),
                ]),
                RouteValidator::ERROR_CODE_ROOT_SCALAR,
            ],
            'Invalid root (array)' => [
                new Route(breadcrumb: [
                    Route::ROOT => Dummy::array(),
                ]),
                RouteValidator::ERROR_CODE_ROOT_SCALAR,
            ],
            'Invalid root (bool)' => [
                new Route(breadcrumb: [
                    Route::ROOT => Dummy::bool(),
                ]),
                RouteValidator::ERROR_CODE_ROOT_SCALAR,
            ],
            'Defining parent route and root at the same time' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => Dummy::string(),
                    Route::ROOT => Dummy::string(),
                ]),
                RouteValidator::ERROR_CODE_ROOT_AND_PARENT_ROUTE_DEFINED,
            ],
            'Circular reference' => [
                new Route(name: 'route', breadcrumb: [
                    Route::PARENT_ROUTE => 'route',
                ]),
                RouteValidator::ERROR_CODE_CIRCULAR_REFERENCE,
            ],
        ];
    }

    public static function getValidatesRouteTestScenarios(): array
    {
        return [
            'Root' => [
                new Route(
                    breadcrumb: [
                        Route::ROOT => Dummy::string(),
                        Route::EXPRESSION => Dummy::string(),
                    ]
                ),
            ],
            'Parent route' => [
                new Route(
                    breadcrumb: [
                        Route::PARENT_ROUTE => Dummy::string(),
                        Route::EXPRESSION => Dummy::string(),
                    ]
                ),
            ],
            'Standalone breadcrumb' => [
                new Route(
                    breadcrumb: [
                        Route::EXPRESSION => Dummy::string(),
                    ]
                ),
            ],
        ];
    }
}

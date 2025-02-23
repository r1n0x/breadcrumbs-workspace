<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Validator;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator;

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
                    Route::PARENT_ROUTE => 1,
                ]),
                RouteValidator::ERROR_CODE_PARENT_ROUTE_STRING,
            ],
            'Invalid parent route (array)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => [],
                ]),
                RouteValidator::ERROR_CODE_PARENT_ROUTE_STRING,
            ],
            'Invalid parent route (boolean)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => false,
                ]),
                RouteValidator::ERROR_CODE_PARENT_ROUTE_STRING,
            ],
            'Invalid expression (number)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => '',
                    Route::EXPRESSION => 1,
                ]),
                RouteValidator::ERROR_CODE_EXPRESSION_STRING,
            ],
            'Invalid expression (array)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => '',
                    Route::EXPRESSION => [],
                ]),
                RouteValidator::ERROR_CODE_EXPRESSION_STRING,
            ],
            'Invalid expression (boolean)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => '',
                    Route::EXPRESSION => false,
                ]),
                RouteValidator::ERROR_CODE_EXPRESSION_STRING,
            ],
            'Invalid pass parameters to expression (integer)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => '',
                    Route::EXPRESSION => '',
                    Route::PASS_PARAMETERS_TO_EXPRESSION => 1,
                ]),
                RouteValidator::ERROR_CODE_PASS_PARAMETERS_TO_EXPRESSION_BOOL,
            ],
            'Invalid pass parameters to expression (array)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => '',
                    Route::EXPRESSION => '',
                    Route::PASS_PARAMETERS_TO_EXPRESSION => 1,
                ]),
                RouteValidator::ERROR_CODE_PASS_PARAMETERS_TO_EXPRESSION_BOOL,
            ],
            'Invalid pass parameters to expression (string)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => '',
                    Route::EXPRESSION => '',
                    Route::PASS_PARAMETERS_TO_EXPRESSION => '',
                ]),
                RouteValidator::ERROR_CODE_PASS_PARAMETERS_TO_EXPRESSION_BOOL,
            ],
            'Invalid root (integer)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => '',
                    Route::EXPRESSION => '',
                    Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                    Route::ROOT => 1,
                ]),
                RouteValidator::ERROR_CODE_ROOT_SCALAR,
            ],
            'Invalid root (array)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => '',
                    Route::EXPRESSION => '',
                    Route::PASS_PARAMETERS_TO_EXPRESSION => true,
                    Route::ROOT => [],
                ]),
                RouteValidator::ERROR_CODE_ROOT_SCALAR,
            ],
            'Invalid root (boolean)' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => '',
                    Route::EXPRESSION => '',
                    Route::PASS_PARAMETERS_TO_EXPRESSION => false,
                    Route::ROOT => false,
                ]),
                RouteValidator::ERROR_CODE_ROOT_SCALAR,
            ],
            'Defining both parent route and root' => [
                new Route(breadcrumb: [
                    Route::PARENT_ROUTE => '',
                    Route::EXPRESSION => '',
                    Route::PASS_PARAMETERS_TO_EXPRESSION => false,
                    Route::ROOT => '',
                ]),
                RouteValidator::ERROR_CODE_ROOT_AND_PARENT_ROUTE_DEFINED,
            ],
        ];
    }
}

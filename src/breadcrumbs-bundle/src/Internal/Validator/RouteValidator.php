<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Validator;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Exception\RouteValidationException;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteValidator
{
    public const int ERROR_CODE_PARENT_ROUTE_STRING = 10;
    public const int ERROR_CODE_EXPRESSION_STRING = 11;
    public const int ERROR_CODE_PASS_PARAMETERS_TO_EXPRESSION_BOOL = 12;
    public const int ERROR_CODE_ROOT_SCALAR = 13;
    public const int ERROR_CODE_ROOT_AND_PARENT_ROUTE_DEFINED = 14;

    /**
     * @throws RouteValidationException
     */
    public function validate(Route $route): void
    {
        $breadcrumb = $route->getBreadcrumb();
        if (array_key_exists(Route::PARENT_ROUTE, $breadcrumb) && !is_string($breadcrumb[Route::PARENT_ROUTE])) {
            throw new RouteValidationException(sprintf(
                'Value of breadcrumbs "%s" must be a string',
                Route::PARENT_ROUTE
            ), self::ERROR_CODE_PARENT_ROUTE_STRING);
        }
        if (array_key_exists(Route::EXPRESSION, $breadcrumb) && !is_string($breadcrumb[Route::EXPRESSION])) {
            throw new RouteValidationException(sprintf(
                'Value of breadcrumbs "%s" must be a string',
                Route::EXPRESSION
            ), self::ERROR_CODE_EXPRESSION_STRING);
        }
        if (array_key_exists(Route::PASS_PARAMETERS_TO_EXPRESSION, $breadcrumb) && !is_bool($breadcrumb[Route::PASS_PARAMETERS_TO_EXPRESSION])) {
            throw new RouteValidationException(sprintf(
                'Value of breadcrumbs "%s" must be a bool',
                Route::PASS_PARAMETERS_TO_EXPRESSION
            ), self::ERROR_CODE_PASS_PARAMETERS_TO_EXPRESSION_BOOL);
        }
        if (array_key_exists(Route::ROOT, $breadcrumb) && !is_string($breadcrumb[Route::ROOT])) {
            throw new RouteValidationException(sprintf(
                'Value of breadcrumbs "%s" must be a string',
                Route::ROOT
            ), self::ERROR_CODE_ROOT_SCALAR);
        }
        if (array_key_exists(Route::ROOT, $breadcrumb) && array_key_exists(Route::PARENT_ROUTE, $breadcrumb)) {
            throw new RouteValidationException(sprintf(
                'Breadcrumbs cannot define a "%s" and "%s" keys at once - you must use only one',
                Route::ROOT,
                Route::PARENT_ROUTE
            ), self::ERROR_CODE_ROOT_AND_PARENT_ROUTE_DEFINED);
        }
    }
}

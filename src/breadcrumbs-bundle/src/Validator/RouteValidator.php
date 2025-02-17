<?php

namespace R1n0x\BreadcrumbsBundle\Validator;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Exception\RuntimeException;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteValidator
{
    public function validate(Route $route): void
    {
        $breadcrumb = $route->getBreadcrumb();
        if (array_key_exists(Route::PARENT_ROUTE, $breadcrumb) && !is_scalar($breadcrumb[Route::PARENT_ROUTE])) {
            throw new RuntimeException(sprintf(
                'Value of breadcrumbs "%s" must be a scalar',
                Route::PARENT_ROUTE
            ));
        }
        if (array_key_exists(Route::EXPRESSION, $breadcrumb) && !is_string($breadcrumb[Route::EXPRESSION])) {
            throw new RuntimeException(sprintf(
                'Value of breadcrumbs "%s" must be a string',
                Route::EXPRESSION
            ));
        }
        if (array_key_exists(Route::PASS_PARAMETERS_TO_EXPRESSION, $breadcrumb) && !is_bool($breadcrumb[Route::PASS_PARAMETERS_TO_EXPRESSION])) {
            throw new RuntimeException(sprintf(
                'Value of breadcrumbs "%s" must be a bool',
                Route::PASS_PARAMETERS_TO_EXPRESSION
            ));
        }
        if (array_key_exists(Route::ROOT, $breadcrumb) && !is_string($breadcrumb[Route::ROOT])) {
            throw new RuntimeException(sprintf(
                'Value of breadcrumbs "%s" must be a string',
                Route::ROOT
            ));
        }
        if (array_key_exists(Route::ROOT, $breadcrumb) && array_key_exists(Route::PARENT_ROUTE, $breadcrumb)) {
            throw new RuntimeException(sprintf(
                'Breadcrumbs cannot define a "%s" and "%s" keys at once - you must use only one',
                Route::ROOT,
                Route::PARENT_ROUTE
            ));
        }
    }
}
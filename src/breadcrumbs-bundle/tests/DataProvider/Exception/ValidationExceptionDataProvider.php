<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Exception;

use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\ValidationContext;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ValidationExceptionDataProvider
{
    public static function getMessageIsReadableTestScenarios(): array
    {
        return [
            'Invalid route parameters' => [
                (function () {
                    $context = new ValidationContext();
                    $context
                        ->addRouteParameterViolation(
                            'route-b7ca076b-1b03-4217-864e-b9b2fb188aa6',
                            'parameter-7ed7fd91-c7c1-4890-9122-fbf83de0b6f4'
                        );
                    $context
                        ->addRouteParameterViolation(
                            'route-b7ca076b-1b03-4217-864e-b9b2fb188aa6',
                            'parameter-00ff7b4e-ce81-4595-a458-f850c46202eb'
                        );
                    $context
                        ->addRouteParameterViolation(
                            'route-b2302df9-69e7-4105-b63c-521f1a13efea',
                            'parameter-50cd1bd9-4862-4cdc-83eb-6422ebf4562b'
                        );

                    return $context;
                })(),
                <<<'TEXT'
Breadcrumb validation failed:
Parameters [parameter-7ed7fd91-c7c1-4890-9122-fbf83de0b6f4, parameter-00ff7b4e-ce81-4595-a458-f850c46202eb] required by route "route-b7ca076b-1b03-4217-864e-b9b2fb188aa6" were not set.
Parameters [parameter-50cd1bd9-4862-4cdc-83eb-6422ebf4562b] required by route "route-b2302df9-69e7-4105-b63c-521f1a13efea" were not set.

TEXT,
            ],
            'Invalid route variables' => [
                (function () {
                    $context = new ValidationContext();
                    $context
                        ->addRouteVariableViolation(
                            'route-b7ca076b-f3235298-c3b3-4579-bd55-6f769ff035d7',
                            'variable-67829435-998c-4c73-adda-ff5b43c6fdad'
                        );
                    $context
                        ->addRouteVariableViolation(
                            'route-b7ca076b-f3235298-c3b3-4579-bd55-6f769ff035d7',
                            'variable-0d7d204d-fa4d-40e3-869a-ffbce8e659d9'
                        );
                    $context
                        ->addRouteVariableViolation(
                            'route-5270c8cc-b4bd-4f3b-a94a-06561692ffc0',
                            'variable-e1620779-b947-47a9-b9c5-e3050afd8b60'
                        );

                    return $context;
                })(),
                <<<'TEXT'
Breadcrumb validation failed:
Variables [variable-67829435-998c-4c73-adda-ff5b43c6fdad, variable-0d7d204d-fa4d-40e3-869a-ffbce8e659d9] required by route "route-b7ca076b-f3235298-c3b3-4579-bd55-6f769ff035d7" were not set.
Variables [variable-e1620779-b947-47a9-b9c5-e3050afd8b60] required by route "route-5270c8cc-b4bd-4f3b-a94a-06561692ffc0" were not set.

TEXT,
            ],
            'Invalid root variables' => [
                (function () {
                    $context = new ValidationContext();
                    $context
                        ->addRootVariableViolation(
                            'route-cf6712d0-f6f9-4dc0-afdc-1bf7b4ea29b4',
                            'variable-a8e82b30-3fe7-4e70-be77-120522758c73'
                        );
                    $context
                        ->addRootVariableViolation(
                            'route-cf6712d0-f6f9-4dc0-afdc-1bf7b4ea29b4',
                            'variable-da2a5fdf-44f9-40c4-b5c1-7619f4eabe62'
                        );
                    $context
                        ->addRootVariableViolation(
                            'route-3c2d7a76-d67a-45c3-8725-adf9ec4c7d83',
                            'variable-baa1a12d-d92c-4492-a740-815dc17bfa53'
                        );

                    return $context;
                })(),
                <<<'TEXT'
Breadcrumb validation failed:
Variables [variable-a8e82b30-3fe7-4e70-be77-120522758c73, variable-da2a5fdf-44f9-40c4-b5c1-7619f4eabe62] required by root "route-cf6712d0-f6f9-4dc0-afdc-1bf7b4ea29b4" were not set.
Variables [variable-baa1a12d-d92c-4492-a740-815dc17bfa53] required by root "route-3c2d7a76-d67a-45c3-8725-adf9ec4c7d83" were not set.

TEXT,
            ],
        ];
    }
}

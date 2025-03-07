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
                                    'route-6ad9df41-44c7-4147-a807-92c1d85887b2',
                                    'parameter-5d7ae081-621e-46bf-80f3-d9ca9ec3d986'
                                );
                            $context
                                ->addRouteParameterViolation(
                                    'route-6ad9df41-44c7-4147-a807-92c1d85887b2',
                                    'parameter-8673b26e-1d3e-4a99-bf53-186c38d6f257'
                                );
                            $context
                                ->addRouteParameterViolation(
                                    'route-3e644ed4-4d28-4bfa-983e-89318994c8c7',
                                    'parameter-3981d115-0123-4797-b42a-ed491e78b6e8'
                                );
                        },
                        2,
                        2,
                        0,
                        [
                            self::createRouteError(
                                'route-6ad9df41-44c7-4147-a807-92c1d85887b2',
                                ErrorType::Parameter,
                                [
                                    'parameter-5d7ae081-621e-46bf-80f3-d9ca9ec3d986',
                                    'parameter-8673b26e-1d3e-4a99-bf53-186c38d6f257',
                                ]
                            ),
                            self::createRouteError(
                                'route-3e644ed4-4d28-4bfa-983e-89318994c8c7',
                                ErrorType::Parameter,
                                [
                                    'parameter-3981d115-0123-4797-b42a-ed491e78b6e8',
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
                                    'route-9324a61d-2150-4fb2-9452-e4d49270119d',
                                    'variable-ba1cfacc-057e-4782-8d8b-a2408c1a4918'
                                );
                            $context
                                ->addRouteVariableViolation(
                                    'route-9324a61d-2150-4fb2-9452-e4d49270119d',
                                    'variable-aa90c4b3-bda7-448b-90b8-f9e08f26a4ff'
                                );
                            $context
                                ->addRouteVariableViolation(
                                    'route-e5325839-a3a1-4851-baba-defb5dc7bd06',
                                    'variable-03f93d63-51e9-4df4-8786-700ed4e112ac'
                                );
                        },
                        2,
                        2,
                        0,
                        [
                            self::createRouteError(
                                'route-9324a61d-2150-4fb2-9452-e4d49270119d',
                                ErrorType::Variable,
                                [],
                                [
                                    'variable-ba1cfacc-057e-4782-8d8b-a2408c1a4918',
                                    'variable-aa90c4b3-bda7-448b-90b8-f9e08f26a4ff',
                                ]
                            ),
                            self::createRouteError(
                                'route-e5325839-a3a1-4851-baba-defb5dc7bd06',
                                ErrorType::Variable,
                                [],
                                [
                                    'variable-03f93d63-51e9-4df4-8786-700ed4e112ac',
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
                                    'route-21b82ee0-d891-43e2-ac1d-d571d94b328c',
                                    'variable-995be50f-2192-4eda-8e4d-bd20ce2f8dc6'
                                );
                            $context
                                ->addRootVariableViolation(
                                    'route-21b82ee0-d891-43e2-ac1d-d571d94b328c',
                                    'variable-7d361580-61b4-4c1a-9b23-1970978ad460'
                                );
                            $context
                                ->addRootVariableViolation(
                                    'route-4ef994ce-147b-4cbf-b034-f5aee8302fca',
                                    'variable-fd4418c5-40a6-4f83-8c3b-b4dc5bf6a204'
                                );
                        },
                        2,
                        0,
                        2,
                        [
                            self::createRootError(
                                'route-21b82ee0-d891-43e2-ac1d-d571d94b328c',
                                [
                                    'variable-995be50f-2192-4eda-8e4d-bd20ce2f8dc6',
                                    'variable-7d361580-61b4-4c1a-9b23-1970978ad460',
                                ]
                            ),
                            self::createRootError(
                                'route-4ef994ce-147b-4cbf-b034-f5aee8302fca',
                                [
                                    'variable-fd4418c5-40a6-4f83-8c3b-b4dc5bf6a204',
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
                                    'route-a87974b3-d7d0-420f-82ad-e86de9562126',
                                    'variable-995be50f-2192-4eda-8e4d-bd20ce2f8dc6'
                                );
                            $context
                                ->addRootVariableViolation(
                                    'route-a87974b3-d7d0-420f-82ad-e86de9562126',
                                    'variable-86c33c6e-5dbd-4dff-8bf5-012cc803b147'
                                );
                            $context
                                ->addRootVariableViolation(
                                    'route-450f72de-3af9-43a8-8b41-5818c0a0f77c',
                                    'variable-d344c518-0125-4064-bf06-af89a00d412b'
                                );
                            $context
                                ->addRouteVariableViolation(
                                    'route-9be7278f-971c-45ff-9904-0a2bf490da1c',
                                    'variable-83ed6d87-d673-432f-b789-e93c3c529922'
                                );
                            $context
                                ->addRouteParameterViolation(
                                    'route-9be7278f-971c-45ff-9904-0a2bf490da1c',
                                    'parameter-703a4dc0-41a3-4714-a9c8-510b48830ad1'
                                );
                            $context
                                ->addRouteParameterViolation(
                                    'route-9be7278f-971c-45ff-9904-0a2bf490da1c',
                                    'parameter-36c3dd66-f94f-4a72-96f6-9c966e05febc'
                                );
                            $context
                                ->addRouteVariableViolation(
                                    'route-9be7278f-971c-45ff-9904-0a2bf490da1c',
                                    'variable-6cb1b0a0-cc62-406f-9c90-02c22ea6dd55'
                                );
                            $context
                                ->addRouteParameterViolation(
                                    'route-70eadf23-9174-4b3f-be7b-220e21dec8c4',
                                    'parameter-4b984a14-ae41-4780-99e1-936920a303c1'
                                );
                        },
                        5,
                        3,
                        2,
                        [
                            self::createRouteError(
                                'route-9be7278f-971c-45ff-9904-0a2bf490da1c',
                                ErrorType::Variable,
                                [],
                                [
                                    'variable-83ed6d87-d673-432f-b789-e93c3c529922',
                                    'variable-6cb1b0a0-cc62-406f-9c90-02c22ea6dd55',
                                ]
                            ),
                            self::createRouteError(
                                'route-9be7278f-971c-45ff-9904-0a2bf490da1c',
                                ErrorType::Parameter,
                                [
                                    'parameter-703a4dc0-41a3-4714-a9c8-510b48830ad1',
                                    'parameter-36c3dd66-f94f-4a72-96f6-9c966e05febc',
                                ],
                            ),
                            self::createRouteError(
                                'route-70eadf23-9174-4b3f-be7b-220e21dec8c4',
                                ErrorType::Parameter,
                                [
                                    'parameter-4b984a14-ae41-4780-99e1-936920a303c1',
                                ],
                            ),
                            self::createRootError(
                                'route-a87974b3-d7d0-420f-82ad-e86de9562126',
                                [
                                    'variable-995be50f-2192-4eda-8e4d-bd20ce2f8dc6',
                                    'variable-86c33c6e-5dbd-4dff-8bf5-012cc803b147',
                                ]
                            ),
                            self::createRootError(
                                'route-450f72de-3af9-43a8-8b41-5818c0a0f77c',
                                [
                                    'variable-d344c518-0125-4064-bf06-af89a00d412b',
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

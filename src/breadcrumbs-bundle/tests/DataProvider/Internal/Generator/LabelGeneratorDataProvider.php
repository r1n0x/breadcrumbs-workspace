<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Generator;

use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Tests\Provider\LabelVariablesProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class LabelGeneratorDataProvider
{
    public static function getGeneratesLabelTestScenarios(): array
    {
        return [
            // at the same time tests if result is cast to string
            'Using global variables' => [
                LabelVariablesProviderProvider::createWithVariables([
                    new Variable('some_value1', 5),
                ]),
                new RootBreadcrumbDefinition(
                    Unused::null(),
                    'some_value1 + 2',
                    Unused::string(),
                    [
                        'some_value1',
                    ]
                ),
                '7',
            ],
            'Using optional variables' => [
                LabelVariablesProviderProvider::createWithVariables([
                    new Variable('some_value1', null),
                ]),
                new RootBreadcrumbDefinition(
                    Unused::null(),
                    'some_value1 ~ "fi"',
                    Unused::string(),
                    [
                        'some_value1',
                    ]
                ),
                'fi',
            ],
            'Using scoped variables' => [
                LabelVariablesProviderProvider::createWithVariables([
                    new Variable(
                        'array2',
                        [
                            '4b8c7233-fe2b-4fd2-a264-8b75bd5fbc04' => '1',
                        ],
                        'route-93c88516-aebb-4b98-ab0f-321a442af8ad'
                    ),
                ]),
                new RouteBreadcrumbDefinition(
                    'route-93c88516-aebb-4b98-ab0f-321a442af8ad',
                    "'3' ~ '2' ~ array2[\"4b8c7233-fe2b-4fd2-a264-8b75bd5fbc04\"]",
                    Unused::string(),
                    Unused::string(),
                    Unused::bool(),
                    [],
                    [
                        'array2',
                    ]
                ),
                '321',
            ],
        ];
    }
}

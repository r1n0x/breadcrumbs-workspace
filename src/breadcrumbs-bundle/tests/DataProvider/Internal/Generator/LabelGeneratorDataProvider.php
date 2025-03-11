<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Generator;

use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\LabelVariablesProviderFake;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class LabelGeneratorDataProvider
{
    public static function getGeneratesLabelTestScenarios(): array
    {
        return [
            'Global variables' => [
                LabelVariablesProviderFake::createWithVariables([
                    new Variable('name', 5),
                ]),
                new RootBreadcrumbDefinition(
                    Dummy::null(),
                    'name + 2',
                    Dummy::string(),
                    [
                        'name',
                    ]
                ),
                '7',
            ],
            'Scoped variables' => [
                LabelVariablesProviderFake::createWithVariables([
                    new Variable(
                        'array',
                        [
                            'key' => '1',
                        ],
                        'route'
                    ),
                ]),
                new RouteBreadcrumbDefinition(
                    'route',
                    "'3' ~ '2' ~ array[\"key\"]",
                    Dummy::string(),
                    Dummy::string(),
                    Dummy::bool(),
                    [],
                    [
                        'array',
                    ]
                ),
                '321',
            ],
        ];
    }
}

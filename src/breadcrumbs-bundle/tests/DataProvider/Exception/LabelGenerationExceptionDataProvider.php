<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\DataProvider\Exception;

use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\Provider\Unused;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class LabelGenerationExceptionDataProvider
{
    public static function getIsGoodMessageTestScenarios(): array
    {
        return [
            'Route breadcrumb' => [
                new RouteBreadcrumbDefinition(
                    'route-bad7d22a-60f1-4982-adf4-a6d95ae17004',
                    'expression-b66783ae-f1bb-4c52-830a-85f9c152ffb5',
                    Unused::string(),
                    Unused::string(),
                    Unused::bool()
                ),
                sprintf(
                    'Error occurred when evaluating breadcrumb expression "%s" for route "%s"',
                    'expression-b66783ae-f1bb-4c52-830a-85f9c152ffb5',
                    'route-bad7d22a-60f1-4982-adf4-a6d95ae17004'
                ),
            ],
            'Root breadcrumb' => [
                new RootBreadcrumbDefinition(
                    Unused::string(),
                    'expression-71215cb4-d74a-47b4-96ad-0b21aa614cb6',
                    'name-2486b414-6c92-4d50-bbde-05e42337e6d7',
                    Unused::array()
                ),
                sprintf(
                    'Error occurred when evaluating breadcrumb expression "%s" for root "%s"',
                    'expression-71215cb4-d74a-47b4-96ad-0b21aa614cb6',
                    'name-2486b414-6c92-4d50-bbde-05e42337e6d7'
                ),
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\LabelGenerationException;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(LabelGenerationException::class)]
#[UsesClass(BreadcrumbDefinition::class)]
#[UsesClass(RouteBreadcrumbDefinition::class)]
#[UsesClass(RootBreadcrumbDefinition::class)]
class LabelGenerationExceptionTest extends TestCase
{
    #[Test]
    public function messageForRouteIsReadable(): void
    {
        $exception = new LabelGenerationException(new RouteBreadcrumbDefinition(
            'route-bad7d22a-60f1-4982-adf4-a6d95ae17004',
            'expression-b66783ae-f1bb-4c52-830a-85f9c152ffb5',
            Unused::string(),
            Unused::string(),
            Unused::bool()
        ));
        $this->assertEquals(LabelGenerationException::CODE_ROUTE, $exception->getCode());
        $this->assertEquals(sprintf(
            'Error occurred when evaluating breadcrumb expression "%s" for route "%s"',
            'expression-b66783ae-f1bb-4c52-830a-85f9c152ffb5',
            'route-bad7d22a-60f1-4982-adf4-a6d95ae17004'
        ), $exception->getMessage());
    }

    #[Test]
    public function messageForRootIsReadable(): void
    {
        $exception = new LabelGenerationException(new RootBreadcrumbDefinition(
            Unused::string(),
            'expression-71215cb4-d74a-47b4-96ad-0b21aa614cb6',
            'name-2486b414-6c92-4d50-bbde-05e42337e6d7',
            Unused::array()
        ));
        $this->assertEquals(LabelGenerationException::CODE_ROOT, $exception->getCode());
        $this->assertEquals(sprintf(
            'Error occurred when evaluating breadcrumb expression "%s" for root "%s"',
            'expression-71215cb4-d74a-47b4-96ad-0b21aa614cb6',
            'name-2486b414-6c92-4d50-bbde-05e42337e6d7'
        ), $exception->getMessage());
    }
}

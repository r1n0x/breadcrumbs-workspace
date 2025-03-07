<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\LabelGenerationException;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\Exception\LabelGenerationExceptionDataProvider;

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
    #[DataProviderExternal(LabelGenerationExceptionDataProvider::class, 'getMessageIsReadableTestScenarios')]
    public function messageIsReadable(
        BreadcrumbDefinition $definition,
        string $expectedMessage
    ): void {
        $exception = new LabelGenerationException($definition);
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }
}

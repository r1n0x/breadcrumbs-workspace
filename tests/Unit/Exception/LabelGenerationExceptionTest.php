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
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;

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
            'route',
            'expression',
            Dummy::string(),
            Dummy::string(),
            Dummy::bool()
        ));

        $this->assertEquals(LabelGenerationException::CODE_ROUTE, $exception->getCode());
        $this->assertEquals(
            'Error occurred when evaluating breadcrumb expression "expression" for route "route"',
            $exception->getMessage()
        );
    }

    #[Test]
    public function messageForRootIsReadable(): void
    {
        $exception = new LabelGenerationException(new RootBreadcrumbDefinition(
            Dummy::string(),
            'expression',
            'root',
            Dummy::array()
        ));

        $this->assertEquals(LabelGenerationException::CODE_ROOT, $exception->getCode());
        $this->assertEquals(
            'Error occurred when evaluating breadcrumb expression "expression" for root "root"',
            $exception->getMessage()
        );
    }
}

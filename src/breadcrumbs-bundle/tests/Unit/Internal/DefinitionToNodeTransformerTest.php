<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\UnknownRootException;
use R1n0x\BreadcrumbsBundle\Exception\UnknownRouteException;
use R1n0x\BreadcrumbsBundle\Internal\DefinitionToNodeTransformer;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\DefinitionToNodeTransformerDataProvider;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\DefinitionToNodeTransformerFake;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(DefinitionToNodeTransformer::class)]
#[UsesClass(BreadcrumbNode::class)]
#[UsesClass(RouteBreadcrumbDefinition::class)]
#[UsesClass(RootBreadcrumbDefinition::class)]
class DefinitionToNodeTransformerTest extends TestCase
{
    #[Test]
    #[TestDox('Throws exception, when unknown root is provided')]
    public function throwsExceptionWhenUnknownRootIsProvided(): void
    {
        $this->expectException(UnknownRootException::class);

        $service = $this->getService();

        $definition = new RouteBreadcrumbDefinition(
            Dummy::string(),
            Dummy::string(),
            null,
            'undefined_root',
            Dummy::bool(),
            [],
            []
        );

        $service->transform($definition, [$definition]);
    }

    #[Test]
    #[TestDox('Throws exception, when unknown route is provided')]
    public function throwsExceptionWhenUnknownRouteIsProvided(): void
    {
        $this->expectException(UnknownRouteException::class);

        $service = $this->getService();

        $definition = new RouteBreadcrumbDefinition(
            Dummy::string(),
            Dummy::string(),
            'undefined_route',
            null,
            Dummy::bool(),
            [],
            []
        );

        $service->transform($definition, [$definition]);
    }

    #[Test]
    #[DataProviderExternal(DefinitionToNodeTransformerDataProvider::class, 'getTransformsDefinitionToNodeTestScenarios')]
    public function transformsDefinitionToNode(
        RouteBreadcrumbDefinition $definition,
        array $definitions,
        BreadcrumbNode $expectedNode
    ): void {
        $service = $this->getService();

        $this->assertEquals($expectedNode, $service->transform($definition, $definitions));
    }

    private function getService(): DefinitionToNodeTransformer
    {
        return DefinitionToNodeTransformerFake::create();
    }
}

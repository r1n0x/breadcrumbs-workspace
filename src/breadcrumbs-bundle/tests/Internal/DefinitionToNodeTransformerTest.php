<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Internal;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\UnknownRootException;
use R1n0x\BreadcrumbsBundle\Exception\UnknownRouteException;
use R1n0x\BreadcrumbsBundle\Internal\DefinitionToNodeTransformer;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\DefinitionToNodeTransformerDataProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(DefinitionToNodeTransformer::class)]
#[CoversClass(BreadcrumbNode::class)]
#[CoversClass(RouteBreadcrumbDefinition::class)]
#[CoversClass(RootBreadcrumbDefinition::class)]
class DefinitionToNodeTransformerTest extends TestCase
{
    /**
     * @param array<int, BreadcrumbDefinition> $definitions
     */
    #[Test]
    #[DataProviderExternal(DefinitionToNodeTransformerDataProvider::class, 'getTransformsTestScenarios')]
    public function transforms(RouteBreadcrumbDefinition $definition, array $definitions, BreadcrumbNode $node): void
    {
        $transformer = $this->getDefinitionToNodeTransformer();
        $this->assertEquals($node, $transformer->transform($definition, $definitions));
    }

    #[Test]
    #[TestDox('Throws exception, when unknown root is provided')]
    public function throwsExceptionWhenUnknownRootIsProvided(): void
    {
        $this->expectException(UnknownRootException::class);
        $transformer = $this->getDefinitionToNodeTransformer();
        $definition = new RouteBreadcrumbDefinition(
            'second_route',
            "'2'",
            null,
            'unknown_root_name',
            true,
            [],
            []
        );
        $transformer->transform($definition, [$definition]);
    }

    #[Test]
    #[TestDox('Throws exception, when unknown route is provided')]
    public function throwsExceptionWhenUnknownRouteIsProvided(): void
    {
        $this->expectException(UnknownRouteException::class);
        $transformer = $this->getDefinitionToNodeTransformer();
        $definition = new RouteBreadcrumbDefinition(
            'second_route',
            "'2'",
            'unknown_parent_route',
            null,
            true,
            [],
            []
        );
        $transformer->transform($definition, [$definition]);
    }

    public function getDefinitionToNodeTransformer(): DefinitionToNodeTransformer
    {
        return new DefinitionToNodeTransformer();
    }
}

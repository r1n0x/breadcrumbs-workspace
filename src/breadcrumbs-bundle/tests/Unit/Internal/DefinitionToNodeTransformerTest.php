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
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\DefinitionToNodeTransformerDataProvider;

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
    /**
     * @param array<int, BreadcrumbDefinition> $definitions
     */
    #[Test]
    #[DataProviderExternal(DefinitionToNodeTransformerDataProvider::class, 'getTransformsDefinitionToNodeTestScenarios')]
    public function transformsDefinitionToNode(
        RouteBreadcrumbDefinition $definition,
        array $definitions,
        BreadcrumbNode $expectedNode
    ): void {
        $transformer = $this->getService();
        $this->assertEquals($expectedNode, $transformer->transform($definition, $definitions));
    }

    #[Test]
    #[TestDox('Throws exception, when unknown root is provided')]
    public function throwsExceptionWhenUnknownRootIsProvided(): void
    {
        $this->expectException(UnknownRootException::class);
        $transformer = $this->getService();
        $definition = new RouteBreadcrumbDefinition(
            'route-b25f0a42-9e30-4c74-9087-1faab483395f',
            'expression-27f03c17-a499-4b9e-8c29-2c5e4a75e897',
            null,
            'root-41152337-a058-427b-9083-a56cd90a8e14',
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
        $transformer = $this->getService();
        $definition = new RouteBreadcrumbDefinition(
            'route-e6816270-29a2-44d9-a848-0ecbbd500934',
            'expression-965460b8-1064-441c-b2c1-506a25e80068',
            'route-65d38e38-f6f5-42eb-885b-18e9af84777e',
            null,
            true,
            [],
            []
        );
        $transformer->transform($definition, [$definition]);
    }

    private function getService(): DefinitionToNodeTransformer
    {
        return new DefinitionToNodeTransformer();
    }
}

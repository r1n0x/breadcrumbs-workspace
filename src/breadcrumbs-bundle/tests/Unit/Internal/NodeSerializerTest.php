<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\NodeSerializerDataProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(NodeSerializer::class)]
#[UsesClass(BreadcrumbDefinition::class)]
#[UsesClass(BreadcrumbNode::class)]
#[UsesClass(RootBreadcrumbDefinition::class)]
#[UsesClass(RouteBreadcrumbDefinition::class)]
#[UsesClass(ParameterDefinition::class)]
class NodeSerializerTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(NodeSerializerDataProvider::class, 'getSerializesNodesTestScenarios')]
    public function serializesNodes(
        BreadcrumbNode $node,
        string $expectedSerializedNode
    ): void {
        $serializer = $this->getService();
        $this->assertEquals($expectedSerializedNode, $serializer->serialize([$node]));
    }

    #[Test]
    #[DataProviderExternal(NodeSerializerDataProvider::class, 'getDeserializesNodesTestScenarios')]
    public function deserializesNodes(
        string $serializedNode,
        BreadcrumbNode $expectedDeserializedNode
    ): void {
        $serializer = $this->getService();
        $this->assertEquals([$expectedDeserializedNode], $serializer->deserialize($serializedNode));
    }

    private function getService(): NodeSerializer
    {
        return new NodeSerializer();
    }
}

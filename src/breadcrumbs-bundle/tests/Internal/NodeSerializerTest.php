<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Internal;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\NodeSerializerDataProvider;

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
class NodeSerializerTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(NodeSerializerDataProvider::class, 'getSerializesTestScenarios')]
    public function serializes(BreadcrumbNode $node, string $serialized): void
    {
        $serializer = $this->getService();
        $this->assertEquals($serialized, $serializer->serialize([$node]));
    }

    #[Test]
    #[DataProviderExternal(NodeSerializerDataProvider::class, 'getDeserializesTestScenarios')]
    public function deserializes(string $serialized, BreadcrumbNode $deserialized): void
    {
        $serializer = $this->getService();
        $this->assertEquals([$deserialized], $serializer->deserialize($serialized));
    }

    private function getService(): NodeSerializer
    {
        return new NodeSerializer();
    }
}

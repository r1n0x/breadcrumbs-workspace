<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Resolver;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Internal\CacheReaderInterface;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver;
use R1n0x\BreadcrumbsBundle\Tests\Provider\NodesResolverProvider;
use R1n0x\BreadcrumbsBundle\Tests\Stub\CacheReaderStub;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(NodesResolver::class)]
#[UsesClass(NodeSerializer::class)]
class NodesResolverTest extends TestCase
{
    #[Test]
    public function resolvesNodeByRouteName(): void
    {
        $node = $this
            ->getService(
                CacheReaderStub::create()
                    ->addNode(new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'route_name',
                            'expression',
                            null,
                            null,
                            false,
                            [],
                            [
                                'variable',
                            ]
                        ),
                        null
                    ))
            )->get('route_name');

        $this->assertInstanceOf(RouteBreadcrumbDefinition::class, $node->getDefinition());
        $this->assertEquals('route_name', $node->getDefinition()->getRouteName());
        $this->assertEquals('expression', $node->getDefinition()->getExpression());
        $this->assertEquals(['variable'], $node->getDefinition()->getVariables());
    }

    #[Test]
    #[TestDox("Resolves null when node for route name doesn't exist")]
    public function resolvesNullWhenNodeForRouteNameDoesntExist(): void
    {
        $node = $this
            ->getService(
                CacheReaderStub::create()
            )->get('route_name');

        $this->assertEquals(null, $node);
    }

    private function getService(CacheReaderInterface $cacheReader): NodesResolver
    {
        return NodesResolverProvider::create($cacheReader);
    }
}

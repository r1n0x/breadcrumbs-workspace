<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Resolver;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Model\Root;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootsResolver;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\RootsResolverFake;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(RootsResolver::class)]
#[UsesClass(Root::class)]
class RootsResolverTest extends TestCase
{
    #[Test]
    public function deserializesArrayIntoObjects(): void
    {
        $roots = $this->getService([
            'root' => [
                Route::EXPRESSION => 'expression',
                'route' => 'route',
            ],
        ])->getRoots();

        $this->arrayHasKey(0)->evaluate($roots);

        $root = $roots[0];
        $this->assertEquals('root', $root->getName());
        $this->assertEquals('expression', $root->getExpression());
        $this->assertEquals('route', $root->getRouteName());
    }

    private function getService(array $config): RootsResolver
    {
        return RootsResolverFake::create($config);
    }
}

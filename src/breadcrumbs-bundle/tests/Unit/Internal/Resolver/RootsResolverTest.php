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
        $name = 'name-d64ba0cf-8872-4e43-81af-c5f9baf4c3b3';
        $expression = 'expression-da385892-3e96-4b27-bfff-10772096df8d';
        $routeName = 'route-52d14737-989c-4274-b857-431ad1026d93';

        $roots = $this->getService([
            $name => [
                Route::EXPRESSION => $expression,
                'route' => $routeName,
            ],
        ])->getRoots();

        $this->arrayHasKey(0)->evaluate($roots);

        $root = $roots[0];
        $this->assertEquals($name, $root->getName());
        $this->assertEquals($expression, $root->getExpression());
        $this->assertEquals($routeName, $root->getRouteName());
    }

    private function getService(array $config): RootsResolver
    {
        return new RootsResolver($config);
    }
}

<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Internal\Resolver;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
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
#[CoversClass(Root::class)]
class RootsResolverTest extends TestCase
{
    #[Test]
    public function resolves(): void
    {
        $name = 'animal';
        $expression = "'Animals'";
        $routeName = 'animal_home';

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

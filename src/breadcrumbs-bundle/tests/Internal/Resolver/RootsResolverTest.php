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
    public function resolves()
    {
        $this->assertEquals(
            [
                new Root('animal', "'Animals'", 'animal_home'),
            ],
            $this->getRootsResolver([
                'animal' => [
                    Route::EXPRESSION => "'Animals'",
                    'route' => 'animal_home',
                ],
            ])->getRoots()
        );
    }

    private function getRootsResolver(array $config): RootsResolver
    {
        return new RootsResolver($config);
    }
}

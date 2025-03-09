<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Resolver;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Exception\InvalidConfigurationException;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootDefinitionsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootsResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\VariablesResolver;
use R1n0x\BreadcrumbsBundle\Tests\Provider\RootsResolverProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\VariablesResolverProvider;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouterStub;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(RootDefinitionsResolver::class)]
#[UsesClass(RootsResolver::class)]
#[UsesClass(VariablesResolver::class)]
#[UsesClass(ParametersResolver::class)]
class RootDefinitionsResolverTest extends TestCase
{
    #[Test]
    #[TestDox('Throws exception, if root references unknown route name')]
    public function throwsExceptionIfRootReferencesUnknownRouteName(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(RootDefinitionsResolver::ERROR_CODE_UNKNOWN_ROUTE_NAME);

        $this
            ->getService(RouterStub::create(), RootsResolverProvider::createWithConfig([
                'root_name' => [
                    Route::EXPRESSION => 'expression',
                    'route' => 'unknown_route_name',
                ],
            ]))
            ->getDefinitions();
    }

    #[Test]
    #[TestDox('Throws exception, if route referenced in root is dynamic')]
    public function throwsExceptionIfRouteReferencedInRootIsDynamic(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(RootDefinitionsResolver::ERROR_CODE_DYNAMIC_ROUTE);

        $this
            ->getService(
                RouterStub::create()
                    ->addRouteStub('route_name', '/route/{dynamic_parameter}'),
                RootsResolverProvider::createWithConfig([
                    'root_name' => [
                        Route::EXPRESSION => 'expression',
                        'route' => 'route_name',
                    ],
                ])
            )
            ->getDefinitions();
    }

    private function getService(
        RouterInterface $router,
        RootsResolver $resolver
    ): RootDefinitionsResolver {
        return new RootDefinitionsResolver(
            $router,
            VariablesResolverProvider::create(),
            new ParametersResolver(),
            $resolver
        );
    }
}

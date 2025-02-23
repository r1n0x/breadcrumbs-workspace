<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Internal\Generator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Internal\Generator\UrlGenerator;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\Generator\UrlGeneratorDataProvider;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouterStub;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(UrlGenerator::class)]
#[UsesClass(BreadcrumbDefinition::class)]
#[UsesClass(RootBreadcrumbDefinition::class)]
#[UsesClass(ParametersResolver::class)]
#[UsesClass(ParametersHolder::class)]
#[UsesClass(Parameter::class)]
#[UsesClass(RouteBreadcrumbDefinition::class)]
class UrlGeneratorTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(UrlGeneratorDataProvider::class, 'getGeneratesTestScenarios')]
    public function generates(callable $scenarioBuilder): void
    {
        $holder = new ParametersHolder();
        $router = new RouterStub();
        [$definition, $expected] = $scenarioBuilder($router, $holder);
        $this->assertEquals($expected, $this->getUrlGenerator($holder, $router)->generate($definition));
    }

    public function getUrlGenerator(ParametersHolder $holder, RouterInterface $router): UrlGenerator
    {
        return new UrlGenerator($holder, $router);
    }
}

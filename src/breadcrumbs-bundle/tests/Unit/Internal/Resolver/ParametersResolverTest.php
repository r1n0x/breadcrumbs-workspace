<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Resolver;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Attribute\Route as BreadcrumbRoute;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;
use Symfony\Component\Routing\Route;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(ParametersResolver::class)]
class ParametersResolverTest extends TestCase
{
    #[Test]
    public function resolvesBreadcrumbParameters(): void
    {
        $parameters = $this->getService()->getParameters(new BreadcrumbRoute(
            '/route/{dynamic_parameter_with_default_value}/{dynamic_parameter}',
            defaults: [
                'dynamic_parameter_with_default_value' => 'dynamic_value',
            ]
        ));

        $this->assertCount(2, $parameters);
        $this->assertEquals('dynamic_parameter_with_default_value', $parameters[0]->getName());
        $this->assertTrue($parameters[0]->isOptional());
        $this->assertEquals('dynamic_value', $parameters[0]->getOptionalValue());

        $this->assertEquals('dynamic_parameter', $parameters[1]->getName());
        $this->assertFalse($parameters[1]->isOptional());
        $this->assertEquals(null, $parameters[1]->getOptionalValue());
    }

    #[Test]
    #[TestDox("Resolves route default parameters, if they're not present in path")]
    public function resolvesRouteDefaultParametersIfTheyreNotPresentInPath(): void
    {
        $parameters = $this->getService()->getParameters(new Route(
            '/route',
            defaults: [
                'dynamic_parameter_with_default_value' => 'dynamic_value',
            ]
        ));

        $this->assertCount(1, $parameters);
        $this->assertEquals('dynamic_parameter_with_default_value', $parameters[0]->getName());
        $this->assertTrue($parameters[0]->isOptional());
        $this->assertEquals('dynamic_value', $parameters[0]->getOptionalValue());
    }

    private function getService(): ParametersResolver
    {
        return new ParametersResolver();
    }
}

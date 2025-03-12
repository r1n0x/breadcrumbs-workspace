<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Holder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\ParameterAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\ParametersHolderFake;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(ParametersHolder::class)]
#[UsesClass(ParameterAlreadyDefinedException::class)]
class ParametersHolderTest extends TestCase
{
    #[Test]
    #[TestDox('Throws exception, when trying to override global parameter')]
    public function throwsExceptionWhenTryingToOverrideGlobalParameter(): void
    {
        $this->expectException(ParameterAlreadyDefinedException::class);

        $this
            ->getService([
                new Parameter(
                    'parameter',
                    null,
                    Dummy::null(),
                    Dummy::null()
                ),
                new Parameter(
                    'parameter',
                    null,
                    Dummy::null(),
                    Dummy::null()
                ),
            ]);
    }

    #[Test]
    #[TestDox('Throws exception, when trying to override scoped parameter')]
    public function throwsExceptionWhenTryingToOverrideScopedParameter(): void
    {
        $this->expectException(ParameterAlreadyDefinedException::class);

        $this
            ->getService([
                new Parameter(
                    'parameter',
                    'route',
                    Dummy::null(),
                    Dummy::null()
                ),
                new Parameter(
                    'parameter',
                    'route',
                    Dummy::null(),
                    Dummy::null()
                ),
            ]);
    }

    #[Test]
    public function returnsGlobalParameter(): void
    {
        $parameter = $this
            ->getService([
                new Parameter(
                    'parameter',
                    null,
                    'path_value',
                    'autowired_value'
                ),
            ])
            ->get('parameter');

        $this->assertEquals('path_value', $parameter->getPathValue());
        $this->assertEquals('autowired_value', $parameter->getAutowiredValue());
        $this->assertEquals('parameter', $parameter->getName());
        $this->assertEquals(null, $parameter->getRouteName());
    }

    #[Test]
    public function returnsScopedParameter(): void
    {
        $parameter = $this
            ->getService([
                new Parameter(
                    'parameter',
                    'route',
                    'path_value',
                    'autowired_value'
                ),
            ])
            ->get('parameter', 'route');

        $this->assertEquals('path_value', $parameter->getPathValue());
        $this->assertEquals('autowired_value', $parameter->getAutowiredValue());
        $this->assertEquals('parameter', $parameter->getName());
        $this->assertEquals('route', $parameter->getRouteName());
    }

    #[Test]
    public function prioritizesScopedParametersOverGlobalParameters(): void
    {
        $parameter = $this
            ->getService([
                new Parameter(
                    'parameter',
                    'route',
                    'path_value_1',
                    'autowired_value_1'
                ),
                new Parameter(
                    'parameter',
                    null,
                    'path_value_2',
                    'autowired_value_2'
                ),
            ])
            ->get('parameter', 'route');

        $this->assertEquals('path_value_1', $parameter->getPathValue());
        $this->assertEquals('autowired_value_1', $parameter->getAutowiredValue());
        $this->assertEquals('parameter', $parameter->getName());
        $this->assertEquals('route', $parameter->getRouteName());
    }

    #[Test]
    public function allowsSettingScopedParameterWithTheSameNameAsGlobalParameter(): void
    {
        $this->expectNotToPerformAssertions();

        $this
            ->getService([
                new Parameter(
                    'parameter',
                    'route',
                    'path_value_1',
                    'autowired_value_1'
                ),
                new Parameter(
                    'parameter',
                    null,
                    'path_value_2',
                    'autowired_value_2'
                ),
            ]);
    }

    #[Test]
    #[TestDox('Fallbacks to global parameter, if scoped parameter is undefined')]
    public function fallbacksToGlobalParameterIfScopedParameterIsUndefined(): void
    {
        $parameter = $this
            ->getService([
                new Parameter(
                    'parameter',
                    null,
                    'path_value',
                    'autowired_value'
                ),
            ])
            ->get('parameter', 'route');

        $this->assertEquals('path_value', $parameter->getPathValue());
        $this->assertEquals('autowired_value', $parameter->getAutowiredValue());
        $this->assertEquals('parameter', $parameter->getName());
        $this->assertEquals(null, $parameter->getRouteName());
    }

    /**
     * @param array<int, Parameter> $parameters
     *
     * @throws ParameterAlreadyDefinedException
     */
    private function getService(array $parameters): ParametersHolder
    {
        return ParametersHolderFake::createWithParameters($parameters);
    }
}

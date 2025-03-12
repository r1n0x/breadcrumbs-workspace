<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedParameterException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\ParametersHolderFake;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(ContextParameterProvider::class)]
#[UsesClass(ParametersHolder::class)]
#[UsesClass(UndefinedParameterException::class)]
class ContextParameterProviderTest extends TestCase
{
    #[Test]
    #[TestDox('Throws exception, if getting undefined parameter')]
    public function throwsExceptionIfGettingUndefinedParameter(): void
    {
        $this->expectException(UndefinedParameterException::class);

        $this
            ->getService(ParametersHolderFake::create())
            ->get('name', null);
    }

    #[Test]
    #[TestDox('Throws exception, if getting undefined parameter for definition')]
    public function throwsExceptionIfGettingUndefinedParameterForDefinition(): void
    {
        $this->expectException(UndefinedParameterException::class);

        $this->getService(ParametersHolderFake::create())
            ->getForDefinition(new RouteBreadcrumbDefinition(
                Dummy::string(),
                Dummy::string(),
                null,
                null,
                Dummy::bool(),
                [
                    new ParameterDefinition(
                        'name_1',
                        false,
                        null
                    ),
                ],
            ), 'name_2', null);
    }

    #[Test]
    public function providesScopedParameter(): void
    {
        $parameter = $this
            ->getService(ParametersHolderFake::createWithParameters([
                new Parameter(
                    'name',
                    'route',
                    'path_value',
                    'autowired_value'
                ),
            ]))
            ->get('name', 'route');

        $this->assertEquals('route', $parameter->getRouteName());
        $this->assertEquals('path_value', $parameter->getPathValue());
        $this->assertEquals('autowired_value', $parameter->getAutowiredValue());
        $this->assertEquals('name', $parameter->getName());
    }

    #[Test]
    public function providesGlobalParameter(): void
    {
        $parameter = $this
            ->getService(ParametersHolderFake::createWithParameters([
                new Parameter(
                    'name',
                    null,
                    'path_value',
                    'autowired_value'
                ),
            ]))
            ->get('name', null);

        $this->assertEquals(null, $parameter->getRouteName());
        $this->assertEquals('path_value', $parameter->getPathValue());
        $this->assertEquals('autowired_value', $parameter->getAutowiredValue());
        $this->assertEquals('name', $parameter->getName());
    }

    #[Test]
    public function providesParameterForDefinition(): void
    {
        $parameter = $this
            ->getService(ParametersHolderFake::createWithParameters([
                new Parameter(
                    'name',
                    null,
                    'path_value',
                    'autowired_value'
                ),
            ]))
            ->getForDefinition(new RouteBreadcrumbDefinition(
                'route',
                Dummy::string(),
                Dummy::string(),
                Dummy::string(),
                Dummy::bool(),
                [
                    new ParameterDefinition(
                        'name',
                        false,
                        null
                    ),
                ],
            ), 'name', 'route');

        $this->assertEquals(null, $parameter->getRouteName());
        $this->assertEquals('path_value', $parameter->getPathValue());
        $this->assertEquals('autowired_value', $parameter->getAutowiredValue());
        $this->assertEquals('name', $parameter->getName());
    }

    private function getService(ParametersHolder $holder): ContextParameterProvider
    {
        return new ContextParameterProvider($holder);
    }
}

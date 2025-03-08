<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedParameterException;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedVariableException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ContextParameterProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\VariablesHolderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(ContextVariableProvider::class)]
#[UsesClass(VariablesHolder::class)]
#[UsesClass(UndefinedVariableException::class)]
#[UsesClass(ContextParameterProvider::class)]
#[UsesClass(ParametersHolder::class)]
#[UsesClass(UndefinedParameterException::class)]
class ContextVariableProviderTest extends TestCase
{
    #[Test]
    #[TestDox('Throws exception, if getting undefined variable')]
    public function throwsExceptionIfGettingUndefinedVariable(): void
    {
        $this->expectException(UndefinedVariableException::class);
        $this
            ->getService(
                VariablesHolderProvider::empty(),
                ContextParameterProviderProvider::empty()
            )->get(
                Unused::rootBreadcrumb(),
                'name-6bcaec78-4ca8-422c-909f-fe1940d5d63d',
                null
            );
    }

    #[Test]
    #[TestDox('Throws exception, if neither variable nor parameter named like variable is defined')]
    public function throwsExceptionIfNeitherVariableNorParameterNamedLikeVariableIsDefined(): void
    {
        $this->expectException(UndefinedVariableException::class);
        $this
            ->getService(
                VariablesHolderProvider::empty(),
                ContextParameterProviderProvider::createWithParameters()
            )->get(
                new RouteBreadcrumbDefinition(
                    'route-9706c0b9-0187-4c9d-b258-13d727af570d',
                    Unused::string(),
                    Unused::string(),
                    Unused::string(),
                    true,
                    [
                        new ParameterDefinition(
                            'parameter-b6e331bf-0103-4221-889d-4685a254ede5',
                            Unused::bool(),
                            Unused::string()
                        ),
                    ]
                ),
                'parameter-b6e331bf-0103-4221-889d-4685a254ede5',
                null
            );
    }

    #[Test]
    public function providesScopedVariable(): void
    {
        $variable = $this
            ->getService(
                VariablesHolderProvider::createWithVariables([
                    new Variable(
                        'name-98b90f1f-0238-43e5-8e6b-a1692cd56765',
                        'value-dac3728a-8244-4206-a47e-cc28f362fe79',
                        'route-4d5eb5d0-7ed5-4a0c-95c2-d392d5f06c80'
                    ),
                ]),
                ContextParameterProviderProvider::empty()
            )->get(
                Unused::rootBreadcrumb(),
                'name-98b90f1f-0238-43e5-8e6b-a1692cd56765',
                'route-4d5eb5d0-7ed5-4a0c-95c2-d392d5f06c80'
            );

        $this->assertEquals('value-dac3728a-8244-4206-a47e-cc28f362fe79', $variable->getValue());
        $this->assertEquals('name-98b90f1f-0238-43e5-8e6b-a1692cd56765', $variable->getName());
        $this->assertEquals('route-4d5eb5d0-7ed5-4a0c-95c2-d392d5f06c80', $variable->getRouteName());
    }

    #[Test]
    public function providesGlobalParameter(): void
    {
        $variable = $this
            ->getService(
                VariablesHolderProvider::createWithVariables([
                    new Variable(
                        'name-01f85a37-3294-41b7-97d1-4269114adc4c',
                        'value-b173c742-5549-433d-9974-664e9815ae40'
                    ),
                ]),
                ContextParameterProviderProvider::empty()
            )->get(
                Unused::rootBreadcrumb(),
                'name-01f85a37-3294-41b7-97d1-4269114adc4c',
                null
            );

        $this->assertEquals('value-b173c742-5549-433d-9974-664e9815ae40', $variable->getValue());
        $this->assertEquals('name-01f85a37-3294-41b7-97d1-4269114adc4c', $variable->getName());
        $this->assertEquals(null, $variable->getRouteName());
    }

    #[Test]
    public function fallbacksParameterValueWhenVariableIsUndefined(): void
    {
        $parameter = $this
            ->getService(
                VariablesHolderProvider::empty(),
                ContextParameterProviderProvider::createWithParameters([
                    new Parameter(
                        'parameter-8d24e6cd-bebc-4699-8d38-835296182bb4',
                        null,
                        'path-value-de0ea01d-f314-469a-a57f-124f85594363',
                        'autowired-value-3a42eb83-8ad9-4354-8fa6-306e2134e412'
                    ),
                ])
            )->get(
                new RouteBreadcrumbDefinition(
                    'route-850e3afe-4125-45f3-a9f3-c1a18d6713bc',
                    Unused::string(),
                    Unused::string(),
                    Unused::string(),
                    true,
                    [
                        new ParameterDefinition(
                            'parameter-8d24e6cd-bebc-4699-8d38-835296182bb4',
                            Unused::bool(),
                            Unused::string()
                        ),
                    ]
                ),
                'parameter-8d24e6cd-bebc-4699-8d38-835296182bb4',
                null
            );

        $this->assertEquals('autowired-value-3a42eb83-8ad9-4354-8fa6-306e2134e412', $parameter->getValue());
        $this->assertEquals('parameter-8d24e6cd-bebc-4699-8d38-835296182bb4', $parameter->getName());
        $this->assertEquals(null, $parameter->getRouteName());
    }

    private function getService(VariablesHolder $holder, ContextParameterProvider $provider): ContextVariableProvider
    {
        return new ContextVariableProvider($holder, $provider);
    }
}

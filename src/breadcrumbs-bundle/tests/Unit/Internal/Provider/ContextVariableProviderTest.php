<?php

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
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\ContextParameterProviderFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\RootBreadcrumbDefinitionFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\VariablesHolderFake;

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
                VariablesHolderFake::create(),
                ContextParameterProviderFake::createWithParameters()
            )
            ->get(RootBreadcrumbDefinitionFake::create(), 'name');
    }

    #[Test]
    #[TestDox('Throws exception, if neither variable nor parameter named like variable is defined')]
    public function throwsExceptionIfNeitherVariableNorParameterNamedLikeVariableIsDefined(): void
    {
        $this->expectException(UndefinedVariableException::class);

        $this
            ->getService(
                VariablesHolderFake::create(),
                ContextParameterProviderFake::createWithParameters()
            )
            ->get(
                new RouteBreadcrumbDefinition(
                    'route',
                    Dummy::string(),
                    Dummy::string(),
                    Dummy::string(),
                    true,
                    [
                        new ParameterDefinition(
                            'parameter_1',
                            Dummy::bool(),
                            Dummy::string()
                        ),
                    ]
                ),
                'parameter_1'
            );
    }

    #[Test]
    public function providesScopedVariable(): void
    {
        $variable = $this
            ->getService(
                VariablesHolderFake::createWithVariables([
                    new Variable(
                        'name',
                        'value',
                        'route'
                    ),
                ]),
                ContextParameterProviderFake::createWithParameters()
            )
            ->get(RootBreadcrumbDefinitionFake::create(), 'name', 'route');

        $this->assertEquals('value', $variable->getValue());
        $this->assertEquals('name', $variable->getName());
        $this->assertEquals('route', $variable->getRouteName());
    }

    #[Test]
    public function providesGlobalParameter(): void
    {
        $variable = $this
            ->getService(
                VariablesHolderFake::createWithVariables([
                    new Variable(
                        'name',
                        'value'
                    ),
                ]),
                ContextParameterProviderFake::createWithParameters()
            )
            ->get(RootBreadcrumbDefinitionFake::create(), 'name');

        $this->assertEquals('value', $variable->getValue());
        $this->assertEquals('name', $variable->getName());
        $this->assertEquals(null, $variable->getRouteName());
    }

    #[Test]
    public function fallbacksParameterValueWhenVariableIsUndefined(): void
    {
        $variable = $this
            ->getService(
                VariablesHolderFake::create(),
                ContextParameterProviderFake::createWithParameters([
                    new Parameter(
                        'parameter',
                        null,
                        'path_value',
                        'autowired_value'
                    ),
                ])
            )->get(
                new RouteBreadcrumbDefinition(
                    'route',
                    Dummy::string(),
                    Dummy::string(),
                    Dummy::string(),
                    true,
                    [
                        new ParameterDefinition(
                            'parameter',
                            Dummy::bool(),
                            Dummy::string()
                        ),
                    ]
                ),
                'parameter',
                null
            );

        $this->assertEquals('autowired_value', $variable->getValue());
        $this->assertEquals('parameter', $variable->getName());
        $this->assertEquals(null, $variable->getRouteName());
    }

    private function getService(
        VariablesHolder $holder,
        ContextParameterProvider $provider
    ): ContextVariableProvider {
        return new ContextVariableProvider($holder, $provider);
    }
}

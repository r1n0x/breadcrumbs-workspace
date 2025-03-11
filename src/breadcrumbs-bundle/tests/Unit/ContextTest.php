<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Context;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\ParametersHolderFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\VariablesHolderFake;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(Context::class)]
#[UsesClass(ParametersHolder::class)]
#[UsesClass(VariablesHolder::class)]
class ContextTest extends TestCase
{
    #[Test]
    public function setsParameterInContext(): void
    {
        $holder = ParametersHolderFake::create();

        $this
            ->getService($holder, VariablesHolderFake::create())
            ->setParameter('parameter', 'value');

        $parameter = $holder->get('parameter');

        $this->assertEquals('parameter', $parameter->getName());
        $this->assertEquals('value', $parameter->getPathValue());
        $this->assertEquals(null, $parameter->getAutowiredValue());
    }

    #[Test]
    public function setsVariableInContext(): void
    {
        $holder = VariablesHolderFake::create();

        $this
            ->getService(ParametersHolderFake::create(), $holder)
            ->setVariable('variable', 'value');

        $parameter = $holder->get('variable');

        $this->assertEquals('variable', $parameter->getName());
        $this->assertEquals('value', $parameter->getValue());
    }

    private function getService(
        ParametersHolder $parametersHolder,
        VariablesHolder $variablesHolder
    ): Context {
        return new Context(
            $parametersHolder,
            $variablesHolder
        );
    }
}

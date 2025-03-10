<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Context;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ParametersHolderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\VariablesHolderProvider;

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
        $holder = ParametersHolderProvider::create();
        $this
            ->getService($holder, VariablesHolderProvider::empty())
            ->setParameter('parameter_name', 'parameter_value');

        $parameter = $holder->get('parameter_name');

        $this->assertEquals('parameter_name', $parameter->getName());
        $this->assertEquals('parameter_value', $parameter->getPathValue());
        $this->assertEquals(null, $parameter->getAutowiredValue());
    }

    #[Test]
    public function setsVariableInContext(): void
    {
        $holder = VariablesHolderProvider::create();
        $this
            ->getService(ParametersHolderProvider::empty(), $holder)
            ->setVariable('variable_name', 'variable_value');

        $parameter = $holder->get('variable_name');

        $this->assertEquals('variable_name', $parameter->getName());
        $this->assertEquals('variable_value', $parameter->getValue());
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

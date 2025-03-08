<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Holder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\VariableAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Tests\Provider\VariablesHolderProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(VariablesHolder::class)]
#[UsesClass(VariableAlreadyDefinedException::class)]
class VariablesHolderTest extends TestCase
{
    private const string MESSAGE_UNEXPECTED_VARIABLE_VALUE = 'Unexpected variable value';
    private const string MESSAGE_UNEXPECTED_VARIABLE_NAME = 'Unexpected variable name';
    private const string MESSAGE_UNEXPECTED_VARIABLE_ROUTE_NAME = 'Unexpected variable route name';

    #[Test]
    #[TestDox('Throws exception, when trying to override global variable')]
    public function throwsExceptionWhenTryingToOverrideGlobalVariable(
    ): void {
        $this->expectException(VariableAlreadyDefinedException::class);
        $this->getService([
            new Variable(
                'variable-8ab56d69-cc2d-4b1a-a7d3-548a3c254d27',
                'value-9bc93416-70a9-4848-8298-8f5abdda0086'
            ),
            new Variable(
                'variable-8ab56d69-cc2d-4b1a-a7d3-548a3c254d27',
                'value-9bc93416-70a9-4848-8298-8f5abdda0086'
            ),
        ]);
    }

    #[Test]
    #[TestDox('Throws exception, when trying to override scoped variable')]
    public function throwsExceptionWhenTryingToOverrideScopedVariable(
    ): void {
        $this->expectException(VariableAlreadyDefinedException::class);
        $this->getService([
            new Variable(
                'variable-afa01fd4-0392-48de-99a0-6210ce8b3af6',
                'value-934fdcdb-d82d-4593-96e0-28f12af99882',
                'route-8c7b64ae-f7be-495e-a78f-e4dc9a61bb31'
            ),
            new Variable(
                'variable-afa01fd4-0392-48de-99a0-6210ce8b3af6',
                'value-d308ce56-597b-4915-9cdd-31ca76b6c615',
                'route-8c7b64ae-f7be-495e-a78f-e4dc9a61bb31'
            ),
        ]);
    }

    #[Test]
    public function returnsGlobalVariable(): void
    {
        $variable = $this->getService([
            new Variable(
                'variable-ee7f2935-da47-46aa-8e74-e55c1535906d',
                'value-f24dd8b0-f957-4b98-aca5-327b0289b265'
            ),
        ])->get('variable-ee7f2935-da47-46aa-8e74-e55c1535906d');

        $this->assertEquals('value-f24dd8b0-f957-4b98-aca5-327b0289b265', $variable->getValue(), self::MESSAGE_UNEXPECTED_VARIABLE_VALUE);
        $this->assertEquals('variable-ee7f2935-da47-46aa-8e74-e55c1535906d', $variable->getName(), self::MESSAGE_UNEXPECTED_VARIABLE_NAME);
        $this->assertEquals(null, $variable->getRouteName(), self::MESSAGE_UNEXPECTED_VARIABLE_ROUTE_NAME);
    }

    #[Test]
    public function returnsScopedVariable(
    ): void {
        $variable = $this->getService([
            new Variable(
                'variable-71b646d7-6034-4662-b14d-2c9d56d7785c',
                'value-fde3e9db-77b0-4172-8320-039c706ee6e0',
                'route-64b5a112-a572-4da5-b1ad-e0e306f6ae91'
            ),
        ])->get('variable-71b646d7-6034-4662-b14d-2c9d56d7785c', 'route-64b5a112-a572-4da5-b1ad-e0e306f6ae91');

        $this->assertEquals('value-fde3e9db-77b0-4172-8320-039c706ee6e0', $variable->getValue(), self::MESSAGE_UNEXPECTED_VARIABLE_VALUE);
        $this->assertEquals('variable-71b646d7-6034-4662-b14d-2c9d56d7785c', $variable->getName(), self::MESSAGE_UNEXPECTED_VARIABLE_NAME);
        $this->assertEquals('route-64b5a112-a572-4da5-b1ad-e0e306f6ae91', $variable->getRouteName(), self::MESSAGE_UNEXPECTED_VARIABLE_ROUTE_NAME);
    }

    #[Test]
    public function prioritizesScopedVariablesOverGlobalVariables(
    ): void {
        $variable = $this->getService([
            new Variable(
                'variable-80e6c8c6-a38b-4fac-af6f-361c5b52bff5',
                'value-d5ec2b25-ef37-42b6-935f-3cf73fd81f0a',
                'route-caec5c26-b440-4258-9dee-645504487cc0'
            ),
            new Variable(
                'variable-80e6c8c6-a38b-4fac-af6f-361c5b52bff5',
                'value-0493c301-1a3c-43ee-9acb-8dd861351168'
            ),
        ])->get('variable-80e6c8c6-a38b-4fac-af6f-361c5b52bff5', 'route-caec5c26-b440-4258-9dee-645504487cc0');

        $this->assertEquals('value-d5ec2b25-ef37-42b6-935f-3cf73fd81f0a', $variable->getValue(), self::MESSAGE_UNEXPECTED_VARIABLE_VALUE);
        $this->assertEquals('variable-80e6c8c6-a38b-4fac-af6f-361c5b52bff5', $variable->getName(), self::MESSAGE_UNEXPECTED_VARIABLE_NAME);
        $this->assertEquals('route-caec5c26-b440-4258-9dee-645504487cc0', $variable->getRouteName(), self::MESSAGE_UNEXPECTED_VARIABLE_ROUTE_NAME);
    }

    /**
     * @param array<int, Variable> $variables
     */
    private function getService(array $variables): VariablesHolder
    {
        return VariablesHolderProvider::createWithVariables($variables);
    }
}

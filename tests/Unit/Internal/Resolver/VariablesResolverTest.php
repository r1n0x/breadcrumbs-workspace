<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Resolver;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\VariablesResolverException;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\VariablesResolver;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\VariablesResolverFake;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(VariablesResolver::class)]
class VariablesResolverTest extends TestCase
{
    #[Test]
    #[TestDox('Throws exception, when invalid expression is provided')]
    public function throwsExceptionWhenInvalidExpressionIsProvided(): void
    {
        $this->expectException(VariablesResolverException::class);

        $this->getService()->getVariables("'1' ~ 2' ~ '3'");
    }

    #[Test]
    public function resolvesVariablesFromExpression(): void
    {
        $variables = $this
            ->getService()
            ->getVariables('variable_name.property + variable_name2 + array["key"]');

        $this->assertEquals(['variable_name', 'variable_name2', 'array'], $variables);
    }

    private function getService(): VariablesResolver
    {
        return VariablesResolverFake::create();
    }
}

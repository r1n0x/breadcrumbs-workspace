<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\VariableAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(VariableAlreadyDefinedException::class)]
#[UsesClass(Variable::class)]
class VariableAlreadyDefinedExceptionTest extends TestCase
{
    #[Test]
    public function messageForGlobalParameterIsReadable(): void
    {
        $exception = new VariableAlreadyDefinedException(new Variable(
            'variable',
            null
        ));

        $this->assertEquals(VariableAlreadyDefinedException::CODE_GLOBAL, $exception->getCode());
        $this->assertEquals('Global variable named "variable" is already defined', $exception->getMessage());
    }

    #[Test]
    public function messageForScopedParameterIsReadable(): void
    {
        $exception = new VariableAlreadyDefinedException(new Variable(
            'variable',
            Dummy::null(),
            'route'
        ));

        $this->assertEquals(VariableAlreadyDefinedException::CODE_SCOPE, $exception->getCode());
        $this->assertEquals('Scoped variable named "variable" for route "route" is already defined', $exception->getMessage());
    }
}

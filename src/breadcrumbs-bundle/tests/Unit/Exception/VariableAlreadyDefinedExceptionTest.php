<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\VariableAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

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
            'name-3d4c45fb-68dc-4322-aa88-29ef5f0dae31',
            null
        ));
        $this->assertEquals(VariableAlreadyDefinedException::CODE_GLOBAL, $exception->getCode());
        $this->assertEquals(sprintf(
            'Global variable named "%s" is already defined',
            'name-3d4c45fb-68dc-4322-aa88-29ef5f0dae31'
        ), $exception->getMessage());
    }

    #[Test]
    public function messageForScopedParameterIsReadable(): void
    {
        $exception = new VariableAlreadyDefinedException(new Variable(
            'name-1cbf5f81-72c1-43b6-b058-b87357285a88',
            Unused::null(),
            'route-26af1a66-dad4-4ac9-a2c2-4a4d35a022c5'
        ));
        $this->assertEquals(VariableAlreadyDefinedException::CODE_SCOPE, $exception->getCode());
        $this->assertEquals(sprintf(
            'Scoped variable named "%s" for route "%s" is already defined',
            'name-1cbf5f81-72c1-43b6-b058-b87357285a88',
            'route-26af1a66-dad4-4ac9-a2c2-4a4d35a022c5'
        ), $exception->getMessage());
    }
}

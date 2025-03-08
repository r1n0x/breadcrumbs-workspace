<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\ParameterAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(ParameterAlreadyDefinedException::class)]
#[UsesClass(Parameter::class)]
class ParameterAlreadyDefinedExceptionTest extends TestCase
{
    #[Test]
    public function messageForGlobalParameterIsReadable(): void
    {
        $exception = new ParameterAlreadyDefinedException(new Parameter(
            'name-3d1e13d4-43d6-44ec-b151-8a5bf50a76ce',
            null,
            Unused::null(),
            Unused::null()
        ));
        $this->assertEquals(ParameterAlreadyDefinedException::CODE_GLOBAL, $exception->getCode());
        $this->assertEquals(sprintf(
            'Global parameter named "%s" is already defined',
            'name-3d1e13d4-43d6-44ec-b151-8a5bf50a76ce'
        ), $exception->getMessage());
    }

    #[Test]
    public function messageForScopedParameterIsReadable(): void
    {
        $exception = new ParameterAlreadyDefinedException(new Parameter(
            'name-3b0b12b2-a80a-40d0-9fb2-774fe47874cd',
            'route-5d6c28d3-bcb6-4834-a123-e6136b15db9a',
            Unused::null(),
            Unused::null()
        ));
        $this->assertEquals(ParameterAlreadyDefinedException::CODE_SCOPE, $exception->getCode());
        $this->assertEquals(sprintf(
            'Scoped parameter named "%s" for route "%s" is already defined',
            'name-3b0b12b2-a80a-40d0-9fb2-774fe47874cd',
            'route-5d6c28d3-bcb6-4834-a123-e6136b15db9a'
        ), $exception->getMessage());
    }
}

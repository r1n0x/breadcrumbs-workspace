<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedVariableException;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(UndefinedVariableException::class)]
class UndefinedVariableExceptionTest extends TestCase
{
    #[Test]
    public function messageIsReadable(): void
    {
        $exception = new UndefinedVariableException('name-9b354888-0f2d-49d5-b864-9d89f7b08b81');
        $this->assertEquals(sprintf(
            'Variable named "%s" is undefined',
            'name-9b354888-0f2d-49d5-b864-9d89f7b08b81'
        ), $exception->getMessage());
    }
}

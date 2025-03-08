<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedParameterException;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(UndefinedParameterException::class)]
class UndefinedParameterExceptionTest extends TestCase
{
    #[Test]
    public function messageIsReadable(): void
    {
        $exception = new UndefinedParameterException('name-37bcbba4-9007-4d0f-b581-426bff3d09d4');
        $this->assertEquals(sprintf(
            'Parameter named "%s" is undefined',
            'name-37bcbba4-9007-4d0f-b581-426bff3d09d4'
        ), $exception->getMessage());
    }
}

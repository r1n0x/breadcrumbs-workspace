<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\ParameterAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;

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
            'parameter',
            null,
            Dummy::null(),
            Dummy::null()
        ));

        $this->assertEquals(ParameterAlreadyDefinedException::CODE_GLOBAL, $exception->getCode());
        $this->assertEquals(
            'Global parameter named "parameter" is already defined',
            $exception->getMessage()
        );
    }

    #[Test]
    public function messageForScopedParameterIsReadable(): void
    {
        $exception = new ParameterAlreadyDefinedException(new Parameter(
            'parameter',
            'route',
            Dummy::null(),
            Dummy::null()
        ));

        $this->assertEquals(ParameterAlreadyDefinedException::CODE_SCOPE, $exception->getCode());
        $this->assertEquals(
            'Scoped parameter named "parameter" for route "route" is already defined',
            $exception->getMessage()
        );
    }
}

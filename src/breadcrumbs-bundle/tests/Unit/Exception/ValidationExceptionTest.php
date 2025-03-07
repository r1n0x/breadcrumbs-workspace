<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\Error;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RootError;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RouteError;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\ValidationContext;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\Exception\ValidationExceptionDataProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(ValidationException::class)]
#[UsesClass(Error::class)]
#[UsesClass(RouteError::class)]
#[UsesClass(RootError::class)]
#[UsesClass(ValidationContext::class)]
class ValidationExceptionTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(ValidationExceptionDataProvider::class, 'getMessageIsReadableTestScenarios')]
    public function messageIsReadable(
        ValidationContext $context,
        string $expectedMessage
    ): void {
        $exception = new ValidationException($context);
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }
}

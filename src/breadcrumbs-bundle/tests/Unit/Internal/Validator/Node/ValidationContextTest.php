<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Validator\Node;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\Error;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RootError;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RouteError;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\ValidationContext;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Validator\Node\ValidationContextDataProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(ValidationContext::class)]
#[UsesClass(Error::class)]
#[UsesClass(RouteError::class)]
#[UsesClass(RootError::class)]
class ValidationContextTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(ValidationContextDataProvider::class, 'getContainsErrorsTestScenarios')]
    public function containsErrors(
        /* @var callable(ValidationContext): void $contextBuilder */
        callable $contextBuilder,
        int $expectedErrorsCount,
        int $expectedRouteErrorsCount,
        int $expectedRootErrorsCount,
        /* @var array<int, Error> $expectedErrors */
        array $expectedErrors
    ): void {
        $context = new ValidationContext();

        $contextBuilder($context);

        $errors = $context->getErrors();

        $this->assertEquals($expectedErrorsCount > 0, $context->hasErrors());
        $this->assertCount($expectedErrorsCount, $errors);
        $this->assertCount($expectedRouteErrorsCount, array_filter($errors, fn (Error $error) => $error instanceof RouteError));
        $this->assertCount($expectedRootErrorsCount, array_filter($errors, fn (Error $error) => $error instanceof RootError));

        foreach ($errors as $index => $error) {
            $expectedError = $expectedErrors[$index] ?? null;
            $this->assertNotNull($expectedError);
            $this->assertInstanceOf(get_class($expectedError), $error);
            $this->assertEquals($expectedError->getType(), $error->getType());
            $this->assertEquals($expectedError->getNames(), $error->getNames());
        }
    }
}

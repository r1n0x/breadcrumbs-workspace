<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\Error;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RootError;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RouteError;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\ValidationContext;

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
    public function messageForRouteParametersIsReadable(): void
    {
        $context = new ValidationContext();
        $context
            ->addRouteParameterViolation(
                'route_1',
                'parameter_1'
            );
        $context
            ->addRouteParameterViolation(
                'route_1',
                'parameter_2'
            );
        $context
            ->addRouteParameterViolation(
                'route_2',
                'parameter_3'
            );

        $exception = new ValidationException($context);

        $this->assertEquals(<<<'TEXT'
Breadcrumb validation failed:
Parameters [parameter_1, parameter_2] required by route "route_1" were not set.
Parameters [parameter_3] required by route "route_2" were not set.

TEXT, $exception->getMessage());
    }

    #[Test]
    public function messageForRouteVariablesIsReadable(): void
    {
        $context = new ValidationContext();
        $context
            ->addRouteVariableViolation(
                'route_1',
                'variable_1'
            );
        $context
            ->addRouteVariableViolation(
                'route_1',
                'variable_2'
            );
        $context
            ->addRouteVariableViolation(
                'route_2',
                'variable_3'
            );

        $exception = new ValidationException($context);

        $this->assertEquals(<<<'TEXT'
Breadcrumb validation failed:
Variables [variable_1, variable_2] required by route "route_1" were not set.
Variables [variable_3] required by route "route_2" were not set.

TEXT, $exception->getMessage());
    }

    #[Test]
    public function messageForRootIsReadable(): void
    {
        $context = new ValidationContext();
        $context
            ->addRootVariableViolation(
                'root',
                'variable_1'
            );
        $context
            ->addRootVariableViolation(
                'root',
                'variable_2'
            );

        $exception = new ValidationException($context);

        $this->assertEquals(<<<'TEXT'
Breadcrumb validation failed:
Variables [variable_1, variable_2] required by root "root" were not set.

TEXT, $exception->getMessage());
    }
}

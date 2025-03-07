<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Validator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Validator\RouteValidatorDataProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(RouteValidator::class)]
class RouteValidatorTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(RouteValidatorDataProvider::class, 'getThrowsExceptionTestScenarios')]
    public function throwsException(
        Route $route,
        int $expectedErrorCode
    ): void {
        $this->expectExceptionCode($expectedErrorCode);
        $this->getService()->validate($route);
    }

    #[Test]
    #[DataProviderExternal(RouteValidatorDataProvider::class, 'getValidatesRouteTestScenarios')]
    public function validatesRoute(
        Route $route
    ): void {
        $this->expectNotToPerformAssertions();
        $this->getService()->validate($route);
    }

    private function getService(): RouteValidator
    {
        return new RouteValidator();
    }
}

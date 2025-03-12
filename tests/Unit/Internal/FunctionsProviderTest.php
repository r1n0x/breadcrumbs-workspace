<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal;

use ArrayObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Internal\Provider\FunctionsProvider;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(FunctionsProvider::class)]
class FunctionsProviderTest extends TestCase
{
    #[Test]
    public function providesFunctions(): void
    {
        $service = $this->getService(new ArrayObject([
            new class implements ExpressionFunctionProviderInterface {
                public function getFunctions(): array
                {
                    return [
                        Dummy::string(),
                        Dummy::string(),
                        Dummy::string(),
                    ];
                }
            },
            new class implements ExpressionFunctionProviderInterface {
                public function getFunctions(): array
                {
                    return [
                        Dummy::string(),
                    ];
                }
            },
        ]));

        $this->assertCount(4, $service->getFunctions());
    }

    #[Test]
    public function providesProviders(): void
    {
        $service = $this->getService(new ArrayObject([
            new class implements ExpressionFunctionProviderInterface {
                public function getFunctions(): array
                {
                    return [];
                }
            },
            new class implements ExpressionFunctionProviderInterface {
                public function getFunctions(): array
                {
                    return [];
                }
            },
            new class implements ExpressionFunctionProviderInterface {
                public function getFunctions(): array
                {
                    return [];
                }
            },
        ]));

        $this->assertCount(3, $service->getProviders());
    }

    /**
     * @param iterable<ExpressionFunctionProviderInterface> $providers
     */
    public function getService(iterable $providers): FunctionsProvider
    {
        return new FunctionsProvider($providers);
    }
}

<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal;

use ArrayObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Internal\FunctionsProvider;
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
        $provider = $this->getService(new ArrayObject([
            new class implements ExpressionFunctionProviderInterface {
                public function getFunctions(): array
                {
                    return [
                        'function-bbefd909-32b6-4f43-9be2-060e43b2954b',
                        'function-db88b7ca-0583-4f98-a698-1904d01e5f1f',
                        'function-e440cee0-6e4f-45ba-921a-4858d97fbffa',
                    ];
                }
            },
            new class implements ExpressionFunctionProviderInterface {
                public function getFunctions(): array
                {
                    return [
                        'function-f9c40344-e377-44b7-96f8-b9c0dbdda219',
                    ];
                }
            },
        ]));

        $this->assertCount(4, $provider->getFunctions());
    }

    #[Test]
    public function providesProviders(): void
    {
        $provider = $this->getService(new ArrayObject([
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

        $this->assertCount(3, $provider->getProviders());
    }

    /**
     * @param iterable<ExpressionFunctionProviderInterface> $providers
     */
    public function getService(iterable $providers): FunctionsProvider
    {
        return new FunctionsProvider($providers);
    }
}

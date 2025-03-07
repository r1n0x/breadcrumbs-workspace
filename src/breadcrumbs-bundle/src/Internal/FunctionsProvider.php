<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class FunctionsProvider
{
    public function __construct(
        /** @var iterable<ExpressionFunctionProviderInterface> */
        private iterable $providers
    ) {}

    /**
     * @return array<int, ExpressionFunction>
     */
    public function getFunctions(): array
    {
        $functions = [];
        foreach ($this->providers as $provider) {
            $functions = array_merge($functions, $provider->getFunctions());
        }

        return $functions;
    }

    /**
     * @return array<int, ExpressionFunctionProviderInterface>
     */
    public function getProviders(): array
    {
        return array_values(iterator_to_array($this->providers));
    }
}

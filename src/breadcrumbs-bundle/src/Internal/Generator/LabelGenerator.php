<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Generator;

use R1n0x\BreadcrumbsBundle\Exception\LabelGenerationException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Throwable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class LabelGenerator
{
    public function __construct(
        private readonly ExpressionLanguage $expressionLanguage
    ) {}

    /**
     * @throws LabelGenerationException
     */
    public function generate(BreadcrumbDefinition $definition, VariablesHolder $holder): string
    {
        try {
            /* @phpstan-ignore return.type */
            return $this->expressionLanguage->evaluate($definition->getExpression(), $this->getVariables($definition, $holder));
        } catch (Throwable $e) {
            throw new LabelGenerationException($definition, previous: $e);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getVariables(BreadcrumbDefinition $definition, VariablesHolder $holder): array
    {
        $routeName = $definition->getRouteName();
        $variables = [];
        foreach ($definition->getVariables() as $variableName) {
            $value = $holder->getValue($variableName, $routeName)
                ?? $holder->getValue($variableName);
            $variables[$variableName] = VariablesHolder::OPTIONAL_VARIABLE === $value ? null : $value;
        }

        return $variables;
    }
}

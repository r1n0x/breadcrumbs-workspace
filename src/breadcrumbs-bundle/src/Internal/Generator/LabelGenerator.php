<?php

namespace R1n0x\BreadcrumbsBundle\Internal\Generator;

use R1n0x\BreadcrumbsBundle\Exception\RuntimeException;
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
        private readonly VariablesHolder $holder,
        private readonly ExpressionLanguage $expressionLanguage
    ) {}

    public function generate(BreadcrumbDefinition $definition)
    {
        try {
            return $this->expressionLanguage->evaluate($definition->getExpression(), $this->getVariables($definition));
        } catch (Throwable $e) {
            throw new RuntimeException(sprintf(
                'Error occurred when evaluating breadcrumb expression "%s" for route "%s"',
                $definition->getExpression(),
                $definition->getRouteName()
            ), 0, $e);
        }
    }

    public function getVariables(BreadcrumbDefinition $definition): array
    {
        $routeName = $definition->getRouteName();
        $variables = [];
        foreach ($definition->getVariables() as $variableName) {
            $value = $this->holder->getValue($variableName, $routeName)
                ?? $this->holder->getValue($variableName);
            $variables[$variableName] = VariablesHolder::OPTIONAL_VARIABLE === $value ? null : $value;
        }

        return $variables;
    }
}

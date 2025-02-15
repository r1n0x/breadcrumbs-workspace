<?php

namespace R1n0x\BreadcrumbsBundle\Generator;

use R1n0x\BreadcrumbsBundle\Exception\RuntimeException;
use R1n0x\BreadcrumbsBundle\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbDefinition;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Throwable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class LabelGenerator
{
    public function __construct(
        private readonly VariablesHolder    $holder,
        private readonly ExpressionLanguage $expressionLanguage
    )
    {
    }

    public function generate(BreadcrumbDefinition $breadcrumb)
    {
        try {
            return $this->expressionLanguage->evaluate($breadcrumb->getExpression(), $this->getVariables($breadcrumb));
        } catch (Throwable $e) {
            throw new RuntimeException(sprintf(
                'Error occurred when evaluating breadcrumb expression "%s" for route "%s"',
                $breadcrumb->getExpression(),
                $breadcrumb->getRouteName()
            ), 0, $e);
        }
    }

    /**
     * @param BreadcrumbDefinition $breadcrumb
     * @return array
     */
    public function getVariables(BreadcrumbDefinition $breadcrumb): array
    {
        $routeName = $breadcrumb->getRouteName();
        $variables = [];
        foreach ($breadcrumb->getVariables() as $variableName) {
            $variables[$variableName] = $this->holder->getValue($variableName, $routeName)
                ?? $this->holder->getValue($variableName);
        }
        return $variables;
    }
}
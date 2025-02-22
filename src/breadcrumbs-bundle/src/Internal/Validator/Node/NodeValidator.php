<?php

namespace R1n0x\BreadcrumbsBundle\Internal\Validator\Node;

use R1n0x\BreadcrumbsBundle\Exception\RuntimeException;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodeValidator
{
    public function __construct(
        private readonly ParametersHolder $parametersHolder,
        private readonly VariablesHolder $variablesHolder
    ) {}

    /**
     * @throws ValidationException
     */
    public function validate(BreadcrumbNode $node): void
    {
        $context = new ValidationContext();
        $this->doValidate($context, $node);
        if ($context->hasErrors()) {
            throw new ValidationException($this->buildMessage($context));
        }
    }

    private function doValidate(ValidationContext $context, ?BreadcrumbNode $node): void
    {
        if (!$node) {
            return;
        }

        $definition = $node->getDefinition();
        foreach ($definition->getVariables() as $variableName) {
            $value = $this->variablesHolder->getValue($variableName, $definition->getRouteName())
                ?? $this->variablesHolder->getValue($variableName);
            if (!$value) {
                $context->addVariableViolation($definition->getRouteName(), $variableName);
            }
        }

        if ($definition instanceof RouteBreadcrumbDefinition) {
            foreach ($definition->getParameters() as $parameterName) {
                $value = $this->parametersHolder->getValue($parameterName, $definition->getRouteName())
                    ?? $this->parametersHolder->getValue($parameterName);
                if (!$value) {
                    $context->addParameterViolation($definition->getRouteName(), $parameterName);
                }
            }
        }
        $this->doValidate($context, $node->getParent());
    }

    private function buildMessage(ValidationContext $context): string
    {
        $grouped = $context->getGroupedForRoutes();
        $message = 'Breadcrumb validation failed:' . PHP_EOL;
        foreach ($grouped as $group) {
            $type = match ($group[ValidationContext::TYPE]) {
                ValidationContext::TYPE_PARAMETER => 'Parameters',
                ValidationContext::TYPE_VARIABLE => 'Variables',
                default => throw new RuntimeException(sprintf(
                    'Unexpected violation type "%s"',
                    $group[ValidationContext::TYPE]
                ))
            };
            $message .= sprintf(
                '%s [%s] required by route "%s" were not set.' . PHP_EOL,
                $type,
                implode(', ', $group[ValidationContext::NAME]),
                $group[ValidationContext::ROUTE_NAME]
            );
        }

        return $message;
    }
}

<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Validator\Node;

use R1n0x\BreadcrumbsBundle\Context;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\ErrorType;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RootError;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RouteError;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodeContextValidator
{
    /**
     * @throws ValidationException
     */
    public function validate(BreadcrumbNode $node, Context $context): void
    {
        $validationContext = new ValidationContext();
        $this->doValidate($validationContext, $node, $context);
        if ($validationContext->hasErrors()) {
            throw new ValidationException($this->buildMessage($validationContext));
        }
    }

    private function validateRoute(RouteBreadcrumbDefinition $definition, ValidationContext $validationContext, Context $context): void
    {
        foreach ($definition->getVariables() as $variableName) {
            $value = $context->getVariablesHolder()->getValue($variableName, $definition->getRouteName())
                ?? $context->getVariablesHolder()->getValue($variableName);
            if (null === $value) {
                $validationContext->addRouteVariableViolation($definition->getRouteName(), $variableName);
            }
        }
        foreach ($definition->getParameters() as $parameterDefinition) {
            $parameterName = $parameterDefinition->getName();
            $value = $context->getParametersHolder()->getValue($parameterName, $definition->getRouteName())
                ?? $context->getParametersHolder()->getValue($parameterName) ?? $parameterDefinition->getDefaultValue();
            if (null === $value && !$parameterDefinition->isDefaultValue($value)) {
                $validationContext->addRouteParameterViolation($definition->getRouteName(), $parameterName);
            }
        }
    }

    private function validateRoot(RootBreadcrumbDefinition $definition, ValidationContext $validationContext, Context $context): void
    {
        foreach ($definition->getVariables() as $variableName) {
            $value = $context->getVariablesHolder()->getValue($variableName);
            if (null === $value) {
                $validationContext->addRootVariableViolation($definition->getName(), $variableName);
            }
        }
    }

    private function doValidate(ValidationContext $validationContext, BreadcrumbNode $node, Context $context): void
    {
        $definition = $node->getDefinition();

        match (true) {
            $definition instanceof RouteBreadcrumbDefinition => $this->validateRoute($definition, $validationContext, $context),
            $definition instanceof RootBreadcrumbDefinition => $this->validateRoot($definition, $validationContext, $context)
        };

        $parent = $node->getParent();
        if (null !== $parent) {
            $this->doValidate($validationContext, $parent, $context);
        }
    }

    private function buildMessage(ValidationContext $context): string
    {
        $message = 'Breadcrumb validation failed:' . PHP_EOL;
        foreach ($context->getErrors() as $error) {
            $type = match ($error->getType()) {
                ErrorType::Parameter => 'Parameters',
                ErrorType::Variable => 'Variables',
            };
            $message .= match (true) {
                $error instanceof RouteError => sprintf(
                    '%s [%s] required by route "%s" were not set.' . PHP_EOL,
                    $type,
                    implode(', ', $error->getNames()),
                    $error->getRouteName()
                ),
                $error instanceof RootError => sprintf(
                    '%s [%s] required by root "%s" were not set.' . PHP_EOL,
                    $type,
                    implode(', ', $error->getNames()),
                    $error->getName()
                )
            };
        }

        return $message;
    }
}

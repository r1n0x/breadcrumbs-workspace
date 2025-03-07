<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Validator\Node;

use R1n0x\BreadcrumbsBundle\Context;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;

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
            throw new ValidationException($validationContext);
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
                ?? $context->getParametersHolder()->getValue($parameterName) ?? $parameterDefinition->getOptionalValue();
            if (null === $value && !$parameterDefinition->isOptionalValue($value)) {
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
}

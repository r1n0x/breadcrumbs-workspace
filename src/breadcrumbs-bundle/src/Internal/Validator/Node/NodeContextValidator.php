<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Validator\Node;

use R1n0x\BreadcrumbsBundle\Exception\UndefinedParameterException;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedVariableException;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class NodeContextValidator
{
    public function __construct(
        private ContextVariableProvider $variableProvider,
        private ContextParameterProvider $parameterProvider
    ) {}

    /**
     * @throws ValidationException
     */
    public function validate(BreadcrumbNode $node): void
    {
        $context = new ValidationContext();
        $this->doValidate($context, $node);
        if ($context->hasErrors()) {
            throw new ValidationException($context);
        }
    }

    private function doValidate(ValidationContext $context, BreadcrumbNode $node): void
    {
        $definition = $node->getDefinition();

        match (true) {
            $definition instanceof RouteBreadcrumbDefinition => $this->validateRoute($definition, $context),
            $definition instanceof RootBreadcrumbDefinition => $this->validateRoot($definition, $context)
        };

        $parent = $node->getParent();
        if (null !== $parent) {
            $this->doValidate($context, $parent);
        }
    }

    private function validateRoute(RouteBreadcrumbDefinition $definition, ValidationContext $context): void
    {
        $routeName = $definition->getRouteName();
        foreach ($definition->getVariables() as $variableName) {
            try {
                $this->variableProvider->get($definition, $variableName, $routeName);
            } catch (UndefinedVariableException) {
                $context->addRouteVariableViolation($routeName, $variableName);
            }
        }
        foreach ($definition->getParameters() as $parameterDefinition) {
            $parameterName = $parameterDefinition->getName();

            try {
                $this->parameterProvider->get($parameterName, $routeName);
            } catch (UndefinedParameterException) {
                if ($parameterDefinition->isOptional()) {
                    continue;
                }
                $context->addRouteParameterViolation($routeName, $parameterName);
            }
        }
    }

    private function validateRoot(RootBreadcrumbDefinition $definition, ValidationContext $context): void
    {
        foreach ($definition->getVariables() as $variableName) {
            try {
                $this->variableProvider->get($definition, $variableName, $definition->getRouteName());
            } catch (UndefinedVariableException) {
                $context->addRootVariableViolation($definition->getName(), $variableName);
            }
        }
    }
}

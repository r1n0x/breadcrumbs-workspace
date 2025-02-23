<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Validator\Node;

use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\ErrorType;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RootError;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RouteError;

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

    public function validateRoute(RouteBreadcrumbDefinition $definition, ValidationContext $context): void
    {
        foreach ($definition->getVariables() as $variableName) {
            $value = $this->variablesHolder->getValue($variableName, $definition->getRouteName())
                ?? $this->variablesHolder->getValue($variableName);
            if (null === $value) {
                $context->addRouteVariableViolation($definition->getRouteName(), $variableName);
            }
        }
        foreach ($definition->getParameters() as $parameterName) {
            $value = $this->parametersHolder->getValue($parameterName, $definition->getRouteName())
                ?? $this->parametersHolder->getValue($parameterName);
            if (null === $value) {
                $context->addRouteParameterViolation($definition->getRouteName(), $parameterName);
            }
        }
    }

    public function validateRoot(RootBreadcrumbDefinition $definition, ValidationContext $context): void
    {
        foreach ($definition->getVariables() as $variableName) {
            $value = $this->variablesHolder->getValue($variableName);
            if (null === $value) {
                $context->addRootVariableViolation($definition->getName(), $variableName);
            }
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

<?php

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

    private function doValidate(ValidationContext $context, BreadcrumbNode $node): void
    {
        $definition = $node->getDefinition();

        if ($definition instanceof RouteBreadcrumbDefinition) {
            foreach ($definition->getVariables() as $variableName) {
                $value = $this->variablesHolder->getValue($variableName, $definition->getRouteName())
                    ?? $this->variablesHolder->getValue($variableName);
                if (!$value) {
                    $context->addRouteVariableViolation($definition->getRouteName(), $variableName); /* @phpstan-ignore argument.type */
                }
            }
            foreach ($definition->getParameters() as $parameterName) {
                $value = $this->parametersHolder->getValue($parameterName, $definition->getRouteName())
                    ?? $this->parametersHolder->getValue($parameterName);
                if (!$value) {
                    $context->addRouteParameterViolation($definition->getRouteName(), $parameterName); /* @phpstan-ignore argument.type */
                }
            }
        } elseif ($definition instanceof RootBreadcrumbDefinition) {
            foreach ($definition->getVariables() as $variableName) {
                $value = $this->variablesHolder->getValue($variableName);
                if (!$value) {
                    $context->addRootVariableViolation($definition->getName(), $variableName);
                }
            }
        }
        $parent = $node->getParent();
        if ($parent) {
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
            if ($error instanceof RouteError) {
                $message .= sprintf(
                    '%s [%s] required by route "%s" were not set.' . PHP_EOL,
                    $type,
                    implode(', ', $error->getNames()),
                    $error->getRouteName()
                );
            } elseif ($error instanceof RootError) {
                $message .= sprintf(
                    '%s [%s] required by root "%s" were not set.' . PHP_EOL,
                    $type,
                    implode(', ', $error->getNames()),
                    $error->getName()
                );
            }
        }

        return $message;
    }
}

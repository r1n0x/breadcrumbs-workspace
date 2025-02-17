<?php

namespace R1n0x\BreadcrumbsBundle\Validator\Node;

use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Factory\ViolationMessageFactory;
use R1n0x\BreadcrumbsBundle\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Model\RouteBreadcrumbDefinition;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodeValidator
{
    public function __construct(
        private readonly ParametersHolder        $parametersHolder,
        private readonly VariablesHolder         $variablesHolder,
        private readonly ViolationMessageFactory $messageFactory
    )
    {
    }

    /**
     * @param BreadcrumbNode $node
     * @return void
     * @throws ValidationException
     */
    public function validate(BreadcrumbNode $node): void
    {
        $context = new ValidationContext();
        $this->doValidate($context, $node);
        if ($context->hasErrors()) {
            throw new ValidationException($this->messageFactory->getMessage($context));
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
}
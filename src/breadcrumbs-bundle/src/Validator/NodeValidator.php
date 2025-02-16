<?php

namespace R1n0x\BreadcrumbsBundle\Validator;

use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Factory\ViolationMessageFactory;
use R1n0x\BreadcrumbsBundle\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbNode;

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
     * @param ValidationContext $context
     * @param array<int, BreadcrumbNode> $nodes
     * @param bool $throw
     * @return void
     * @throws ValidationException
     */
    public function validate(ValidationContext $context, array $nodes, bool $throw = false): void
    {
        foreach ($nodes as $node) {
            $this->doValidate($context, $node);
        }
        if ($throw && $context->hasErrors()) {
            throw new ValidationException($this->messageFactory->getMessage($context));
        }
    }

    private function doValidate(ValidationContext $context, ?BreadcrumbNode $node): void
    {
        if (!$node) {
            return;
        }

        foreach ($node->getDefinition()->getVariables() as $variableName) {
            $value = $this->variablesHolder->getValue($variableName, $node->getDefinition()->getRouteName())
                ?? $this->variablesHolder->getValue($variableName);
            if (!$value) {
                $context->addVariableViolation($node->getDefinition()->getRouteName(), $variableName);
            }
        }

        foreach ($node->getDefinition()->getParameters() as $parameterName) {
            $value = $this->parametersHolder->getValue($parameterName, $node->getDefinition()->getRouteName())
                ?? $this->parametersHolder->getValue($parameterName);
            if (!$value) {
                $context->addParameterViolation($node->getDefinition()->getRouteName(), $parameterName);
            }
        }
        $this->doValidate($context, $node->getChild());
    }
}
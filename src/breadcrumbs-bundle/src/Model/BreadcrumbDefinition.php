<?php

namespace R1n0x\BreadcrumbsBundle\Model;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
abstract class BreadcrumbDefinition
{
    public function __construct(
        private readonly string $expression,
        private readonly array  $variables = []
    )
    {
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }
}
<?php

namespace R1n0x\BreadcrumbsBundle\Generator;

use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;
use R1n0x\BreadcrumbsBundle\Storage\ExpressionVariablesStorage;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class LabelGenerator
{
    public function __construct(
        private readonly ExpressionVariablesStorage $storage,
        private readonly ExpressionLanguage         $expressionLanguage
    )
    {
    }

    public function generate(BreadcrumbDao $breadcrumb)
    {
        return $this->expressionLanguage->evaluate($breadcrumb->getExpression(), $this->storage->all());
    }
}
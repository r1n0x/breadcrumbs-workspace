<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Exception;

use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use Throwable;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class LabelGenerationException extends Exception
{
    public function __construct(BreadcrumbDefinition $definition, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($this->buildMessage($definition), $code, $previous);
    }

    private function buildMessage(BreadcrumbDefinition $definition): string
    {
        match (true) {
            $definition instanceof RouteBreadcrumbDefinition => sprintf(
                'Error occurred when evaluating breadcrumb expression "%s" for route "%s"',
                $definition->getExpression(),
                $definition->getRouteName()
            ),
            $definition instanceof RootBreadcrumbDefinition => sprintf(
                'Error occurred when evaluating breadcrumb expression "%s" for root "%s"',
                $definition->getExpression(),
                $definition->getName()
            )
        };

        /* @phpstan-ignore missingType.checkedException */
        throw new RuntimeException();
    }
}

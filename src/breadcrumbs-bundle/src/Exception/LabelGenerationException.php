<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Exception;

use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use Throwable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class LabelGenerationException extends Exception
{
    public const int CODE_ROUTE = 1;
    public const int CODE_ROOT = 2;

    public function __construct(BreadcrumbDefinition $definition, ?Throwable $previous = null)
    {
        parent::__construct(
            $this->buildMessage($definition),
            match (true) {
                $definition instanceof RouteBreadcrumbDefinition => self::CODE_ROUTE,
                $definition instanceof RootBreadcrumbDefinition => self::CODE_ROOT
            },
            $previous
        );
    }

    private function buildMessage(BreadcrumbDefinition $definition): string
    {
        return match (true) {
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
    }
}

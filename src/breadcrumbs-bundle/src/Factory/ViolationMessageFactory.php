<?php

namespace R1n0x\BreadcrumbsBundle\Factory;

use R1n0x\BreadcrumbsBundle\Exception\RuntimeException;
use R1n0x\BreadcrumbsBundle\Validator\Node\ValidationContext;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ViolationMessageFactory
{
    public function getMessage(ValidationContext $context): string
    {
        $grouped = $context->getGroupedForRoutes();
        $message = 'Breadcrumb validation failed:' . PHP_EOL;
        foreach ($grouped as $group) {
            $type = match ($group[ValidationContext::TYPE]) {
                ValidationContext::TYPE_PARAMETER => 'Parameters',
                ValidationContext::TYPE_VARIABLE => 'Variables',
                default => throw new RuntimeException(sprintf(
                    'Unexpected violation type "%s"',
                    $group[ValidationContext::TYPE]
                ))
            };
            $message .= sprintf(
                '%s [%s] required by route "%s" were not set.' . PHP_EOL,
                $type,
                implode(', ', $group[ValidationContext::NAME]),
                $group[ValidationContext::ROUTE_NAME]
            );
        }
        return $message;
    }
}
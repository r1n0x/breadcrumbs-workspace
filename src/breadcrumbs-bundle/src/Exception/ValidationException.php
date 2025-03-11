<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Exception;

use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\ErrorType;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RootError;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RouteError;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\ValidationContext;
use Throwable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final class ValidationException extends BreadcrumbsException
{
    public function __construct(ValidationContext $context, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($this->buildMessage($context), $code, $previous);
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

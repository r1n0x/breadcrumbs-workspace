<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Exception;

use Throwable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final class UndefinedParameterException extends BreadcrumbsException
{
    public function __construct(string $name, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($this->buildMessage($name), $code, previous: $previous);
    }

    private function buildMessage(string $name): string
    {
        return sprintf(
            'Parameter named "%s" is undefined',
            $name
        );
    }
}

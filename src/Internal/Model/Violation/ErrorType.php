<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model\Violation;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
enum ErrorType: string
{
    case Variable = 'variable';
    case Parameter = 'parameter';
}

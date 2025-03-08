<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Exception;

use Exception;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use Throwable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class VariableAlreadyDefinedException extends Exception
{
    public const int CODE_SCOPE = 1;
    public const int CODE_GLOBAL = 2;

    public function __construct(Variable $parameter, ?Throwable $previous = null)
    {
        parent::__construct(
            $this->buildMessage($parameter),
            null !== $parameter->getRouteName()
                ? self::CODE_SCOPE
                : self::CODE_GLOBAL,
            $previous
        );
    }

    private function buildMessage(Variable $variable): string
    {
        if (null !== $variable->getRouteName()) {
            return sprintf(
                'Scoped variable named "%s" for route "%s" is already defined',
                $variable->getName(),
                $variable->getRouteName()
            );
        }

        return sprintf(
            'Global variable named "%s" is already defined',
            $variable->getName()
        );
    }
}

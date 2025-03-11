<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Exception;

use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use Throwable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final class ParameterAlreadyDefinedException extends BreadcrumbsException
{
    public const int CODE_SCOPE = 1;
    public const int CODE_GLOBAL = 2;

    public function __construct(Parameter $parameter, ?Throwable $previous = null)
    {
        parent::__construct(
            $this->buildMessage($parameter),
            null !== $parameter->getRouteName()
                ? self::CODE_SCOPE
                : self::CODE_GLOBAL,
            $previous
        );
    }

    private function buildMessage(Parameter $parameter): string
    {
        if (null !== $parameter->getRouteName()) {
            return sprintf(
                'Scoped parameter named "%s" for route "%s" is already defined',
                $parameter->getName(),
                $parameter->getRouteName()
            );
        }

        return sprintf(
            'Global parameter named "%s" is already defined',
            $parameter->getName()
        );
    }
}

<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model\Violation;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteError extends Error
{
    public function __construct(
        private readonly ErrorType $type,
        private readonly string $routeName
    ) {
        parent::__construct($this->type);
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }
}

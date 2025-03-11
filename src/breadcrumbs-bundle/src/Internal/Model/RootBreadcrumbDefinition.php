<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Model;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class RootBreadcrumbDefinition extends BreadcrumbDefinition
{
    public function __construct(
        ?string $routeName,
        string $expression,
        private string $name,
        array $variables = []
    ) {
        parent::__construct($routeName, $expression, $variables);
    }

    public function getName(): string
    {
        return $this->name;
    }
}

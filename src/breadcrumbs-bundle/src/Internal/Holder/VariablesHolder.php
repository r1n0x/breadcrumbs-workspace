<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Holder;

use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class VariablesHolder
{
    /**
     * @var array<int, Variable>
     */
    private array $variables = [];

    public function set(Variable $variable): void
    {
        $this->variables[] = $variable;
    }

    public function getValue(string $name, ?string $routeName = null): mixed
    {
        if (null !== $routeName) {
            foreach ($this->variables as $variable) {
                if ($variable->getRouteName() === $routeName && $variable->getName() === $name) {
                    return $variable->getValue();
                }
            }

            return null;
        }
        foreach ($this->variables as $variable) {
            if (null === $variable->getRouteName() && $variable->getName() === $name) {
                return $variable->getValue();
            }
        }

        return null;
    }
}

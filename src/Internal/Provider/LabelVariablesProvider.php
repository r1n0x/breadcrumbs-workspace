<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Provider;

use R1n0x\BreadcrumbsBundle\Exception\UndefinedVariableException;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class LabelVariablesProvider
{
    public function __construct(
        private ContextVariableProvider $provider
    ) {}

    /**
     * @return array<string, mixed>
     *
     * @throws UndefinedVariableException
     */
    public function getVariables(BreadcrumbDefinition $definition): array
    {
        $routeName = $definition->getRouteName();
        $variables = [];
        foreach ($definition->getVariables() as $variableName) {
            $variables[$variableName] = $this->provider
                ->get(
                    $definition,
                    $variableName,
                    $routeName
                )
                ->getValue();
        }

        return $variables;
    }
}

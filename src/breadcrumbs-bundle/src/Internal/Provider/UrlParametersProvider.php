<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Provider;

use R1n0x\BreadcrumbsBundle\Exception\UndefinedParameterException;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class UrlParametersProvider
{
    public function __construct(
        private ContextParameterProvider $provider
    ) {}

    /**
     * @return array<string, null|int|string>
     *
     * @throws UndefinedParameterException
     */
    public function getParameters(RouteBreadcrumbDefinition $definition): array
    {
        $parameters = [];
        foreach ($definition->getParameters() as $parameterDefinition) {
            $parameterName = $parameterDefinition->getName();

            try {
                $parameters[$parameterName] = $this->provider->get(
                    $parameterName,
                    $definition->getRouteName()
                )->getPathValue();
            } catch (UndefinedParameterException $e) {
                if (!$parameterDefinition->isOptional()) {
                    throw $e;
                }
                $parameters[$parameterName] = $parameterDefinition->getOptionalValue();
            }
        }

        return $parameters;
    }
}

<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Attribute\Route as BreadcrumbsRoute;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use Symfony\Component\Routing\Route;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ParametersResolver
{
    /**
     * @return array<int, ParameterDefinition>
     */
    public function getParameters(BreadcrumbsRoute|Route $route): array
    {
        /** @var string $path */
        $path = $route->getPath();
        $defaults = $route->getDefaults();
        preg_match_all('/{(.*?)}/m', $path, $matches, PREG_SET_ORDER);

        /** @var array<string, ParameterDefinition> $parameters */
        $parameters = [];
        foreach ($matches as $match) {
            $name = $match[1];
            $parameters[$name] = new ParameterDefinition(
                $name,
                isset($defaults[$name]),
                $defaults[$name] ?? null
            );
        }

        foreach ($defaults as $name => $value) {
            if (isset($parameters[$name]) || '_controller' === $name) {
                continue;
            }
            $parameters[$name] = new ParameterDefinition(
                $name,
                true,
                $value
            );
        }

        return array_values($parameters);
    }
}

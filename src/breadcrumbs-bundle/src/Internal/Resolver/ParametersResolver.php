<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ParametersResolver
{
    /**
     * @return array<int, string>
     */
    public function getParameters(string $path): array
    {
        preg_match_all('/{(.*?)}/m', $path, $matches, PREG_SET_ORDER);
        $parameters = [];
        foreach ($matches as $match) {
            $parameters[] = $match[1];
        }

        return $parameters;
    }
}

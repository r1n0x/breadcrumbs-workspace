<?php

namespace R1n0x\BreadcrumbsBundle\Provider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ParametersProvider
{
    /**
     * @param string $path
     * @return array<int, string>
     */
    public function getParameters(string $path): array
    {
        preg_match_all('/\{([a-zA-Z0-9_]+)}/', $path, $matches);
        return $matches[1];
    }
}
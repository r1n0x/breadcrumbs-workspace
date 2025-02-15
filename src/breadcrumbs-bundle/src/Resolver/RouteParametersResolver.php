<?php

namespace R1n0x\BreadcrumbsBundle\Resolver;

use Symfony\Component\ExpressionLanguage\Lexer;
use Symfony\Component\ExpressionLanguage\Token;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteParametersResolver
{
    /**
     * @param string $path
     * @return array<int, string>
     */
    public function resolve(string $path): array
    {
        preg_match_all('/\{([a-zA-Z0-9_]+)}/', $path, $matches);
        return $matches[1];
    }
}
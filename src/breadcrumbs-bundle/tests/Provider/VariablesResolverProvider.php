<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\Resolver\VariablesResolver;
use Symfony\Component\ExpressionLanguage\Lexer;
use Symfony\Component\ExpressionLanguage\Parser;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class VariablesResolverProvider
{
    public static function create(): VariablesResolver
    {
        return new VariablesResolver(
            new Lexer(),
            new Parser([])
        );
    }
}

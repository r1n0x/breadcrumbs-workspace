<?php

namespace R1n0x\BreadcrumbsBundle\Resolver;

use Symfony\Component\ExpressionLanguage\Lexer;
use Symfony\Component\ExpressionLanguage\Parser;
use Symfony\Component\ExpressionLanguage\SyntaxError;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class VariablesResolver
{
    public function __construct(
        private readonly Lexer $lexer
    )
    {
    }

    /**
     * @param string $expression
     * @return array<int, string>
     */
    public function getVariables(string $expression): array
    {
        $parser = new Parser([]);
        $variables = [];

        // not clean but hey, it works - and only when building cache, so I don't feel like this will improve anything
        while (true) {
            try {
                $stream = $this->lexer->tokenize($expression);
                $parser->parse($stream, $variables);
            } catch (SyntaxError $e) {
                $variableName = explode('"', $e->getMessage())[1];
                $variables[] = $variableName;
                continue;
            }
            break;
        }

        return $variables;
    }
}
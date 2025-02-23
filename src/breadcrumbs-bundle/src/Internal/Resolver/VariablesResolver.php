<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Exception\VariablesResolverException;
use Symfony\Component\ExpressionLanguage\Lexer;
use Symfony\Component\ExpressionLanguage\Parser;
use Symfony\Component\ExpressionLanguage\SyntaxError;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class VariablesResolver
{
    public function __construct(
        private readonly Lexer $lexer,
        private readonly Parser $parser
    ) {}

    /**
     * @return array<int, string>
     *
     * @throws VariablesResolverException
     */
    public function getVariables(string $expression): array
    {
        $variables = [];

        // not clean but hey, it works and only when building cache, so I don't feel like this will cause any major problems
        while (true) {
            try {
                $stream = $this->lexer->tokenize($expression);
                $this->parser->parse($stream, $variables);
            } catch (SyntaxError $e) {
                $message = $e->getMessage();
                if (0 === mb_strpos($message, 'Variable')) {
                    $variableName = explode('"', $message)[1];
                    $variables[] = $variableName;

                    continue;
                }

                throw new VariablesResolverException(previous: $e);
            }

            break;
        }

        return $variables;
    }
}

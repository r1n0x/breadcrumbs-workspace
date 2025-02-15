<?php

namespace R1n0x\BreadcrumbsBundle\Provider;

use Symfony\Component\ExpressionLanguage\Lexer;
use Symfony\Component\ExpressionLanguage\Token;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class VariablesProvider
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
        $parameters = [];
        $tokens = $this->lexer->tokenize($expression);
        while (true) {
            /** @var Token $current */
            $current = $tokens->current;
            if ($current->type === Token::EOF_TYPE) {
                break;
            }
            if ($current->type === Token::NAME_TYPE) {
                $parameters[] = $current->value;
            }
            $tokens->next();
        }
        return $parameters;
    }
}
<?php

namespace R1n0x\BreadcrumbsBundle\Resolver;

use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;
use Symfony\Component\ExpressionLanguage\Lexer;
use Symfony\Component\ExpressionLanguage\Parser;
use Symfony\Component\ExpressionLanguage\Token;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ExpressionVariablesResolver
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
    public function resolve(string $expression): array
    {
        $parameters = [];
        /** @var Token $token */
        foreach($this->lexer->tokenize($expression) as $token) {
            if($token->type === Token::NAME_TYPE) {
                $parameters[] = $token->value;
            }
        }
        return $parameters;
    }
}
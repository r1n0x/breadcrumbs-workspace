<?php

namespace App\Controller;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\BreadcrumbsBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Lexer;
use Symfony\Component\ExpressionLanguage\Parser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[Route(path: '/', name: 'default_', breadcrumb: [Route::EXPRESSION => 'Default'])]
class DefaultController extends AbstractController
{
    #[Route(path: '/zero', name: 'zero', breadcrumb: [Route::EXPRESSION => 'zero', Route::PARENT_ROUTE => 'default'])]
    public function zero(): JsonResponse
    {
        return $this->json(['zero']);
    }

    #[Route(path: '/one', name: 'one', breadcrumb: [Route::EXPRESSION => 'one', Route::PARENT_ROUTE => 'default_zero'])]
    public function one(): Response
    {
        $expression = "a + b +   'string'";
        $parser = new Parser([]);
        $lexer = new Lexer();
        $tokens = $lexer->tokenize($expression);
//        $ast = $parser->parse($tokens);
//        $p = $x->parse("a + b", []);
//        return $this->redirectToRoute('default');
        return $this->json(['one']);
    }

    #[Route(path: '/two/{id}', name: 'two', breadcrumb: [Route::EXPRESSION => 'two', Route::PARENT_ROUTE => 'default_one'])]
    public function two(string $id, Request $request, BreadcrumbsBuilder $tester): JsonResponse
    {
        $tester->build($request);
        return $this->json(['two']);
    }

    #[Route(path: '/three', name: 'three', breadcrumb: [Route::EXPRESSION => 'three', Route::PARENT_ROUTE => 'default_two'])]
    public function three(): JsonResponse
    {
        return $this->json(['three']);
    }
}
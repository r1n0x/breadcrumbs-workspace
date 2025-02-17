<?php

namespace App\Controller;

use App\Entity\Product;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\BreadcrumbsBuilder;
use R1n0x\BreadcrumbsBundle\BreadcrumbsManager;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Lexer;
use Symfony\Component\ExpressionLanguage\Parser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

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
        return $this->json(['one']);
    }

    #[Route(path: '/two/{product}/{suffix}', name: 'two', defaults: ['suffix' => "some kind of default suffix"], breadcrumb: [
        Route::EXPRESSION => 'product.getName() ~ " - " ~ suffix',
        Route::PARENT_ROUTE => 'default_one',
        Route::PASS_PARAMETERS_TO_EXPRESSION => true
    ])]
    public function two(
        #[MapEntity(id: 'product')] Product $product,
        Request                             $request,
        RouterInterface                     $router,
        BreadcrumbsManager                  $breadcrumbsManager
    ): JsonResponse
    {
        $router->getRouteCollection();
        $breadcrumbsManager
            ->setVariable("zero", "VALUE ZERO")
            ->setVariable("one", "VALUE ONE");
        $breadcrumbs = $breadcrumbsManager->build($request);
        $serialized = [];
        foreach ($breadcrumbs as $breadcrumb) {
            $serialized[] = [
                'label' => $breadcrumb->getLabel(),
                'url' => $breadcrumb->getUrl()
            ];
        }
        return $this->json([
            'url' => 'two',
            'breadcrumbs' => $serialized
        ]);
    }
}
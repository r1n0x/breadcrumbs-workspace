<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[Route(path: '/product', name: 'product_')]
class ProductController extends AbstractController
{
    #[Route(path: '/list', name: 'list', breadcrumb: [Route::EXPRESSION => '\'List\'', Route::ROOT => 'product'])]
    public function list(
        ProductRepository $repository
    ): Response
    {
        return $this->render('product/list.html.twig', [
            'products' => $repository->findAll()
        ]);
    }

    #[Route(path: '/{product}/details', name: 'details', breadcrumb: [
        Route::EXPRESSION => "product.getName()",
        Route::ROOT => 'product',
        Route::PASS_PARAMETERS_TO_EXPRESSION => true
    ])]
    public function details(
        #[MapEntity(id: 'product')] Product $product
    ): Response
    {
        return $this->render('product/details.html.twig', [
            'product' => $product
        ]);
    }
}
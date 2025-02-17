<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\BreadcrumbsManager;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Model\Breadcrumb;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[Route(path: '/product', name: 'product_')]
class ProductController extends AbstractController
{
    #[Route(path: '/list', name: 'list', breadcrumb: [Route::EXPRESSION => '\'List\'', Route::ROOT => 'product'])]
    public function list(
        Request            $request,
        ProductRepository  $repository,
        BreadcrumbsManager $manager
    ): JsonResponse
    {
        $items = [];
        foreach ($repository->findAll() as $product) {
            $items[] = $this->transformEntity($product);
        }
        return $this->json([
            'breadcrumbs' => $this->transformBreadcrumbs($manager, $request),
            'items' => $items
        ]);
    }

    #[Route(path: '/{product}/details', name: 'details', breadcrumb: [
        Route::EXPRESSION => "product.getName()",
        Route::ROOT => 'product',
        Route::PASS_PARAMETERS_TO_EXPRESSION => true
    ])]
    public function one(
        #[MapEntity(id: 'product')] Product $product,
        Request                             $request,
        BreadcrumbsManager                  $manager
    ): Response
    {
        return $this->json([
            'breadcrumbs' => $this->transformBreadcrumbs($manager, $request),
            'details' => $this->transformEntity($product)
        ]);
    }

    public function transformEntity(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'url' => $this->generateUrl('product_details', ['product' => $product->getId()])
        ];
    }

    /**
     * @param BreadcrumbsManager $manager
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function transformBreadcrumbs(BreadcrumbsManager $manager, Request $request): array
    {
        return array_map(fn(Breadcrumb $breadcrumb) => [
            'label' => $breadcrumb->getLabel(),
            'url' => $breadcrumb->getUrl()
        ], $manager->build($request));
    }
}
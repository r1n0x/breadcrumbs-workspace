<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
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
#[Route(path: '/book', name: 'book_')]
class BookController extends AbstractController
{
    #[Route(path: '/list', name: 'list', breadcrumb: [Route::EXPRESSION => '\'Books list\''])]
    public function list(
        Request            $request,
        BookRepository  $repository,
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

    #[Route(path: '/{book}/details', name: 'details', breadcrumb: [
        Route::EXPRESSION => "'Book: ' ~ book.getName()",
        Route::PARENT_ROUTE => 'book_list',
        Route::PASS_PARAMETERS_TO_EXPRESSION => true
    ])]
    public function one(
        #[MapEntity(id: 'book')] Book $book,
        Request                       $request,
        BreadcrumbsManager            $manager
    ): Response
    {
        return $this->json([
            'breadcrumbs' => $this->transformBreadcrumbs($manager, $request),
            'details' => $this->transformEntity($book)
        ]);
    }

    private function transformEntity(Book $book): array
    {
        return [
            'id' => $book->getId(),
            'name' => $book->getName(),
            'url' => $this->generateUrl('book_details', ['book' => $book->getId()])
        ];
    }

    /**
     * @param BreadcrumbsManager $manager
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    private function transformBreadcrumbs(BreadcrumbsManager $manager, Request $request): array
    {
        return array_map(fn(Breadcrumb $breadcrumb) => [
            'label' => $breadcrumb->getLabel(),
            'url' => $breadcrumb->getUrl()
        ], $manager->build($request));
    }
}
<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[Route(path: '/book', name: 'book_')]
class BookController extends AbstractController
{
    #[Route(path: '/list', name: 'list', breadcrumb: [Route::EXPRESSION => '\'Books list\''])]
    public function list(
        BookRepository $repository
    ): Response
    {
        return $this->render('book/list.html.twig', [
            'books' => $repository->findAll()
        ]);
    }

    #[Route(path: '/{book}/details', name: 'details', breadcrumb: [
        Route::EXPRESSION => "book.getName()",
        Route::PARENT_ROUTE => 'book_list',
        Route::PASS_PARAMETERS_TO_EXPRESSION => true
    ])]
    public function details(
        #[MapEntity(id: 'book')] Book $book
    ): Response
    {
        return $this->render('book/details.html.twig', [
            'book' => $book
        ]);
    }
}
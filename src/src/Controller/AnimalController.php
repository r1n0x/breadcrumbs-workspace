<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
use R1n0x\BreadcrumbsBundle\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[Route(path: '/animal', name: 'animal_')]
class AnimalController extends AbstractController
{
    #[Route(path: '/list', name: 'list', breadcrumb: [Route::EXPRESSION => '\'List\'', Route::ROOT => 'animal'])]
    public function list(
        AnimalRepository $repository,
    ): Response
    {
        return $this->render('animal/list.html.twig', [
            'animals' => $repository->findAll()
        ]);
    }

    #[Route(path: '/{animal}/details', name: 'details', breadcrumb: [
        Route::EXPRESSION => "animal.getName()",
        Route::PARENT_ROUTE => 'animal_list',
        Route::PASS_PARAMETERS_TO_EXPRESSION => true
    ])]
    public function details(
        #[MapEntity(id: 'animal')] Animal $animal
    ): Response
    {
        return $this->render('animal/details.html.twig', [
            'animal' => $animal
        ]);
    }
}
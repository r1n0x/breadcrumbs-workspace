<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
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
#[Route(path: '/animal', name: 'animal_')]
class AnimalController extends AbstractController
{
    #[Route(path: '/list', name: 'list', breadcrumb: [Route::EXPRESSION => '\'List\'', Route::ROOT => 'animal'])]
    public function list(
        Request            $request,
        AnimalRepository   $repository,
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

    #[Route(path: '/{animal}/details', name: 'details', breadcrumb: [
        Route::EXPRESSION => "animal.getName()",
        Route::PARENT_ROUTE => 'animal_list',
        Route::PASS_PARAMETERS_TO_EXPRESSION => true
    ])]
    public function one(
        #[MapEntity(id: 'animal')] Animal $animal,
        Request                           $request,
        BreadcrumbsManager                $manager
    ): Response
    {
        return $this->json([
            'breadcrumbs' => $this->transformBreadcrumbs($manager, $request),
            'details' => $this->transformEntity($animal)
        ]);
    }

    private function transformEntity(Animal $animal): array
    {
        return [
            'id' => $animal->getId(),
            'name' => $animal->getName(),
            'url' => $this->generateUrl('animal_details', ['animal' => $animal->getId()])
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
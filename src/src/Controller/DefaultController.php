<?php

namespace App\Controller;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Breadcrumb;
use R1n0x\BreadcrumbsBundle\Loader\BreadcrumbsStorage;
use R1n0x\BreadcrumbsBundle\Provider\Tester;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[Route(path: '/', name: 'default_', breadcrumb: [Breadcrumb::LABEL => 'Default'])]
class DefaultController extends AbstractController
{
    #[Route(path: '/zero', name: 'zero', breadcrumb: [Breadcrumb::LABEL => 'zero', Breadcrumb::PARENT_ROUTE => 'default'])]
    public function zero(): JsonResponse
    {
        return $this->json(['zero']);
    }

    #[Route(path: '/one', name: 'one', breadcrumb: [Breadcrumb::LABEL => 'one', Breadcrumb::PARENT_ROUTE => 'default_zero'])]
    public function one(): Response
    {
//        return $this->redirectToRoute('default');
        return $this->json(['one']);
    }

    #[Route(path: '/two/{id}', name: 'two', breadcrumb: [Breadcrumb::LABEL => 'two', Breadcrumb::PARENT_ROUTE => 'default_one'])]
    public function two(string $id, Request $request, Tester $tester): JsonResponse
    {
        $tester->test($request);
        return $this->json(['two']);
    }

    #[Route(path: '/three', name: 'three', breadcrumb: [Breadcrumb::LABEL => 'three', Breadcrumb::PARENT_ROUTE => 'default_two'])]
    public function three(): JsonResponse
    {
        return $this->json(['three']);
    }
}
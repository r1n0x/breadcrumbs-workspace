<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class DefaultController extends AbstractController
{
    #[Route(path: '/', name: 'home')]
    public function list(): Response
    {
        return $this->render('home.html.twig');
    }
}
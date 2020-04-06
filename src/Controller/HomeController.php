<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param EntrepriseRepository $repo
     * @return Response
     */
    public function index(EntrepriseRepository $repo)
    {
        return $this->render('home/index.html.twig',[

        ]);
    }


}

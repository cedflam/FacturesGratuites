<?php

namespace App\Controller;

use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    /**
     * Permet d'afficher la liste des factures
     *
     * @Route("/facture/list", name="facture_list")
     * @param FactureRepository $repo
     * @return Response
     */
    public function factureList(FactureRepository $repo)
    {
        return $this->render('facture/facture-list.html.twig',[
            'lesFactures' => $factures = $repo->findBy(['entreprise'=>$this->getUser()])
        ]);
    }

}

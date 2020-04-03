<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Devis;
use App\Entity\Entreprise;
use App\Entity\Facture;
use App\Form\DevisType;
use App\Repository\DescriptionRepository;
use App\Repository\DevisRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DevisController extends AbstractController
{



    /**
     * Permet de créer un nouveau devis
     *
     * @Route("/devis/new/{id}", name="devis_add")
     *
     * @param Client $client
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws Exception
     */
    public function devisAdd(Client $client, Request $request, EntityManagerInterface $manager)
    {
        $devis = new Devis();
        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //Je crée une nouvelle facture
            $facture = new Facture();
            //J'attribue l'id du devis aux descriptions
            foreach($devis->getDescription() as $description){
                $description->setDevis($devis)
                ;
            }
            //Je paramètre le devis
            $devis->setDateDevis(new \DateTime())
                  ->setFacture($facture)
            ;
            //Je paramètre la facture
            $facture->setDevis($devis)
                ->setClient($client)
                ->setEntreprise($this->getUser())
                ->setMontantHt($devis->getMontantHt())
                ->setMontantTtc($devis->getMontantTtc())
                ->setDateFacture(new \DateTime())
                ->setCrd($devis->getMontantTtc())
            ;
            $manager->persist($facture);
            $manager->persist($devis);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le devis a bien été enregistré !"
            );

            return $this->redirectToRoute('client');
        }

        return $this->render('devis/devis-add.html.twig', [
            'form'=> $form->createView(),
            'client'=>$client

        ]);
    }

    /**
     * Permet de voir la liste des devis pour un client
     *
     * @Route("/devis/show/{id}", name="devis_show")
     *
     * @param Client $client
     * @param FactureRepository $repo
     * @return Response
     */
    public function devisShow(Client $client, FactureRepository $repo)
    {
        return $this->render('devis/devis-list.html.twig',[
           'lesFactures'=> $factures = $repo->findBy(['client'=>$client]),
            'client'=>$client
        ]);
    }

    /**
     * Permet de consulter et d'imprimer un devis
     *
     * @Route("/devis/print/{id}", name="devis_print")
     *
     * @param Devis $devis
     * @param DescriptionRepository $descriptionRepository
     * @return Response
     */
    public function devisPrint(Devis $devis, DescriptionRepository $descriptionRepository)
    {
        return $this->render('devis/devis-print.html.twig',[
            'devis'=> $devis,
            'lesDescriptions'=> $descriptionRepository->findBy(['devis'=>$devis])
        ]);
    }

    /**
     * Permet de voir la liste des devis d'une entreprise
     *
     * @Route("/devis/show-all/{id}", name="devis_show_all")
     *
     * @param Entreprise $entreprise
     * @param FactureRepository $factures
     * @return Response
     */
    public function devisShowAll(Entreprise $entreprise, FactureRepository $factures)
    {
        return $this->render('devis/devis-list-all.html.twig', [
            'lesFactures'=>$factures->findBy(['entreprise'=>$entreprise->getId()]),

        ]);
    }

    /**
     * Permet de modifier un devis
     *
     * @Route("/devis/edit/{id}", name="devis_edit")
     *
     * @param EntityManagerInterface $manager
     * @param Devis $devis
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function devisEdit(EntityManagerInterface $manager, Devis $devis, Request $request)
    {
        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            //J'attribue l'id du devis aux descriptions
            foreach($devis->getDescription() as $description){
                $description->setDevis($devis)
                ;
            }
            //Je récupère la facture correspondante
            $facture = $devis->getFacture();
            $facture->setMontantHt($devis->getMontantHt())
                    ->setMontantTtc($devis->getMontantTtc())
                    ->setDateFacture(new \DateTime())
                    ->setCrd($devis->getMontantTtc());
            //Je paramètre le devis
            $devis->setDateDevis(new \DateTime())
                ->setFacture($facture)
            ;
            //J'enregistre en bdd
            $manager->persist($facture);
            $manager->persist($devis);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le devis a bien été modifié !"
            );

            return $this->redirectToRoute('client');
        }

        return $this->render('devis/devis-edit.html.twig',[
            'form'=>$form->createView(),
            'devis'=>$devis
        ]);
    }



    /**
     * Permet de supprimer un devis
     *
     * @Route("/devis/delete/{id}", name="devis_delete")
     *
     * @param Devis $devis
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function devisDelete(Devis $devis, EntityManagerInterface $manager)
    {

        $manager->remove($devis);
        $manager->flush();

        $this->addFlash('success', "Le devis à été supprimé !");

        return $this->redirectToRoute('client');
    }
}

/*************************Ajax********************/



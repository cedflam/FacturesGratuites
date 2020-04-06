<?php

namespace App\Controller;

use App\Entity\Acompte;
use App\Entity\Facture;
use App\Form\AcompteType;
use App\Repository\AcompteRepository;
use App\Repository\DescriptionRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FactureController extends AbstractController
{
    /**
     * Permet d'afficher la liste des factures
     *
     * @Route("/facture/list", name="facture_list")
     *
     * @Security("is_granted('ROLE_USER') and user.getToken() === '' ")
     *
     * @param FactureRepository $repo
     * @return Response
     */
    public function factureList(FactureRepository $repo)
    {
        return $this->render('facture/facture-list.html.twig',[
            'lesFactures' => $factures = $repo->findBy(['entreprise'=>$this->getUser()])
        ]);
    }

    /**
     * Permet de transmettre l'id de la facture à la modal
     *
     * @Route("facture/edit/{id}", name="facture_edit")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === facture.getEntreprise().getId() and user.getToken() === '' ")
     *
     * @param Facture $facture
     * @return Response
     */
    public function factureEdit(Facture $facture)
    {
        return $this->render('facture/facture-choice.html.twig',[
            'facture'=>$facture
        ]);
    }

    /**
     * Permet d'fficher la facture d'acompte
     *
     * @Route("/facture/acompte/{id}", name="facture_acompte_edit")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === facture.getEntreprise().getId() and user.getToken() === '' ")
     *
     * @param Facture $facture
     * @param DescriptionRepository $descriptionRepository
     * @return Response
     */
    public function factureAcompte(Facture $facture, DescriptionRepository $descriptionRepository)
    {
        return $this->render('facture/facture-acompte.html.twig',[
            'facture'=>$facture,
            'lesDescriptions'=>$descriptionRepository->findBy(['devis'=>$facture->getDevis()])
        ]);
    }

    /**
     * Permet d'fficher la facture finale
     *
     * @Route("/facture/finale/{id}", name="facture_finale_edit")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === facture.getEntreprise().getId() and user.getToken() === '' ")
     *
     * @param Facture $facture
     * @param DescriptionRepository $descriptionRepository
     * @return Response
     */
    public function factureFinale(Facture $facture, DescriptionRepository $descriptionRepository)
    {
        return $this->render('facture/facture.html.twig',[
            'facture'=>$facture,
            'lesDescriptions'=>$descriptionRepository->findBy(['devis'=>$facture->getDevis()])
        ]);
    }

    /**
     * Permet d'jouter un acompte
     *
     * @Route("/facture/acompte/add/{id}", name="acompte_add")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === facture.getEntreprise().getId() and user.getToken() === '' ")
     *
     * @param Facture $facture
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws Exception
     */
    public function acompteAdd(Facture $facture, Request $request, EntityManagerInterface $manager)
    {
        $acompte = new Acompte();
        $form = $this->createForm(AcompteType::class, $acompte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //Je lie l'acompte à la facture
            $acompte->setFacture($facture);
            //Je persist
            $manager->persist($acompte);
            //Calcul de l'acompte total versé
            $factureTotalAcompte = $facture->getTotalAcompte();
            $factureTotalAcompte += $acompte->getMontant();
            $facture->setTotalAcompte($factureTotalAcompte);
            //J'enregistre
            $manager->persist($facture);
            $manager->flush();
            //Calcul du crd
            $crd = $facture->getMontantTtc() - $facture->getTotalAcompte();
            $facture->setCrd($crd)
                    ->setDateFacture(new \DateTime())
            ;
            //J'enregistre
            $manager->persist($facture);
            $manager->flush();
            //Message flash
            $this->addFlash(
                'success',
                "L'acompte a bien été ajouté !"
            );

            return $this->redirectToRoute('facture_list');
        }

            return $this->render('facture/add-acompte.html.twig', [
                'form'=>$form->createView()
            ]);
    }

    /**
     * Permet d'afficher la liste des acomptes
     *
     * @Route("/facture/acompte/show/{id}", name="acompte_show")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === facture.getEntreprise().getId() and user.getToken() === '' ")
     *
     * @param Facture $facture
     * @param AcompteRepository $repo
     * @return Response
     */
    public function acompteEdit(Facture $facture, AcompteRepository $repo)
    {
        $acomptes = $repo->findBy(['facture'=>$facture]);

        return $this->render('facture/show-acompte.html.twig',[
            'acomptes'=> $acomptes,
            'facture'=>$facture

        ]);
    }

    /**
     * Permet de supprimer un acompte
     *
     * @Route("/facture/acompte/delete/{id}", name="acompte_delete")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === acompte.getFacture().getEntreprise().getId() and user.getToken() === '' ")
     *
     * @param EntityManagerInterface $manager
     * @param Acompte $acompte
     * @param FactureRepository $repo
     * @return Response
     * @throws Exception
     */
    public function acompteDelete(EntityManagerInterface $manager, Acompte $acompte, FactureRepository $repo)
    {
        //Je récupère la facture liée à l'acompte
        $facture = $acompte->getFacture();
        //Je supprime l'acompte
        $manager->remove($acompte);
        $manager->flush();
        //Mise à jour de l'acompte total
        $factureTotalAcompte = $facture->getTotalAcompte();
        $factureTotalAcompte -= $acompte->getMontant();
        $facture->setTotalAcompte($factureTotalAcompte);
        //J'enregistre
        $manager->persist($facture);
        $manager->flush();
        //Calcul du crd et mise à jour de la date
        $crd = $facture->getMontantTtc() - $facture->getTotalAcompte();
        $facture->setCrd($crd)
            ->setDateFacture(new \DateTime())
        ;

        $this->addFlash(
            'success',
            "L'acompte a bien été supprimé !"
            );

       return  $this->render('facture/facture-list.html.twig',[
            'lesFactures'=> $repo->findAll()
       ]);
    }
}

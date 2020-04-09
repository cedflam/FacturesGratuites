<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Description;
use App\Entity\Devis;
use App\Entity\Facture;
use App\Form\DevisType;
use App\Repository\DescriptionRepository;
use App\Repository\FactureRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @noinspection PhpUnused
     * @Security("is_granted('ROLE_USER') and user.getId() === client.getEntreprise().getId() and user.getToken() === ''")
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

        if ($form->isSubmitted() && $form->isValid()) {
            //Je crée une nouvelle facture
            $facture = new Facture();
            //J'attribue l'id du devis aux descriptions
            foreach ($devis->getDescription() as $description) {
                $description->setDevis($devis);
                $manager->persist($description);
            }
            //Je paramètre le devis
            $devis->setDateDevis(new DateTime())
                ->setFacture($facture);
            //Je paramètre la facture
            $facture->setDevis($devis)
                ->setClient($client)
                ->setEntreprise($this->getUser())
                ->setMontantHt($devis->getMontantHt())
                ->setMontantTtc($devis->getMontantTtc())
                ->setDateFacture(new DateTime())
                ->setCrd($devis->getMontantTtc());
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
            'form' => $form->createView(),
            'client' => $client

        ]);
    }

    /**
     * Permet de voir la liste des devis pour un client
     *
     * @Route("/devis/show/{id}", name="devis_show")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === client.getEntreprise().getId() and user.getToken() === ''")
     *
     * @noinspection PhpUnused
     * @param Client $client
     * @param FactureRepository $repo
     * @return Response
     */
    public function devisShow(Client $client, FactureRepository $repo)
    {
        return $this->render('devis/devis-list.html.twig', [
            'lesFactures' => $factures = $repo->findBy(['client' => $client]),
            'client' => $client
        ]);
    }

    /**
     * Permet de consulter et d'imprimer un devis
     *
     * @Route("/devis/print/{id}", name="devis_print")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === devis.getFacture().getEntreprise().getId() and user.getToken() === '' ")
     *
     * @noinspection PhpUnused
     * @param Devis $devis
     * @param DescriptionRepository $descriptionRepository
     * @return Response
     */
    public function devisPrint(Devis $devis, DescriptionRepository $descriptionRepository)
    {
        return $this->render('devis/devis-print.html.twig', [
            'devis' => $devis,
            'lesDescriptions' => $descriptionRepository->findBy(['devis' => $devis])
        ]);
    }

    /**
     * Permet de voir la liste des devis d'une entreprise
     *
     * @Route("/devis/show-all", name="devis_show_all")
     *
     * @Security("is_granted('ROLE_USER') ")
     *
     * @noinspection PhpUnused
     * @param FactureRepository $factures
     * @return Response
     */
    public function devisShowAll(FactureRepository $factures)
    {
        return $this->render('devis/devis-list-all.html.twig', [
            'lesFactures' => $factures->findBy(['entreprise' => $this->getUser()]),

        ]);
    }

    /**
     * Permet de modifier un devis
     *
     * @Route("/devis/edit/{id}", name="devis_edit")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === devis.getFacture().getEntreprise().getId() and user.getToken() === '' ")
     *
     * @noinspection PhpUnused
     * @param EntityManagerInterface $manager
     * @param Devis $devis
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function devisEdit(Devis $devis, Request $request, EntityManagerInterface $manager)
    {

        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //J'attribue l'id du devis aux descriptions
            foreach ($devis->getDescription() as $description) {
                $description->setDevis($devis);
                $manager->persist($description);
            }

            //Je paramètre le devis
            $devis->setDateDevis(new DateTime());

            //Je paramètre la facture
            $facture = $devis->getFacture();
            $facture->setDevis($devis)
                ->setEntreprise($this->getUser())
                ->setMontantHt($devis->getMontantHt())
                ->setMontantTtc($devis->getMontantTtc())
                ->setDateFacture(new DateTime())
                ->setCrd($devis->getMontantTtc());

            //J'enregistre
            $manager->persist($devis);
            $manager->flush();




            $this->addFlash(
                'success',
                "Le devis a bien été modifié !"
            );

            return $this->redirectToRoute('devis_show_all');
        }
        return $this->render('devis/devis-edit.html.twig', [
            'form' => $form->createView(),
            'devis' => $devis
        ]);
    }


    /**
     * Permet de supprimer un devis
     *
     * @Route("/devis/delete/{id}", name="devis_delete")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === devis.getFacture().getEntreprise().getId() and user.getToken() === '' ")
     *
     * @noinspection PhpUnused
     * @param Devis $devis
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function devisDelete(Devis $devis, EntityManagerInterface $manager)
    {
        $manager->remove($devis);
        $manager->flush();

        $this->addFlash('success', "Le devis à été supprimé !");

        return $this->redirectToRoute('devis_show_all');
    }

    /*************************Ajax********************/

}


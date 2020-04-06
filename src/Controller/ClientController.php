<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ClientController extends AbstractController
{
    /**
     * Permet de récupérer la liste des clients
     *
     * @Route("/client", name="client")
     *
     * @param ClientRepository $repo
     * @return Response
     */
    public function listClient(ClientRepository $repo)
    {
        return $this->render('client/clients-list.html.twig', [
            'clients'=> $repo->findAll()
        ]);
    }

    /**
     * Permet d'ajouter un nouveau client
     *
     * @Route("client/add", name="client_add")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function addClient(EntityManagerInterface $manager, Request $request)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $client->setEntreprise($this->getUser());

            $manager->persist($client);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre nouveau client a été enregistré !"
            );

            return $this->redirectToRoute('client');
        }

        return $this->render('client/client-add.html.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * Permet de modifier un client
     *
     * @Route("/client/edit/{id}", name="client_edit")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === client.getEntreprise().getId()")
     *
     * @param Client $client
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function editClient(Client $client, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $client->setEntreprise($this->getUser());

            $manager->persist($client);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le client a été modifié avec succès ! "
                );
            return $this->redirectToRoute('client');
        }

        return $this->render('client/client-edit.html.twig',[
            'form'=>$form->createView(),
            'client'=>$client
        ]);
    }

    /**
     * Permet de supprimer un client
     *
     * @Route("/client/delete/{id}", name="client_delete")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === client.getEntreprise().getId()")
     *
     * @param Client $client
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function delete(Client $client, EntityManagerInterface $manager)
    {
        if ($this->getUser() == $client->getEntreprise()){
            $manager->remove($client);
            $manager->flush();

            $this->addFlash('success', "Le client à été supprimé !");

            return $this->redirectToRoute('client');
        }else{
            $this->addFlash(
                'danger',
                "Accès interdit, le numéro de client ne correspond pas à l'un de vos client"
            );

            return $this->redirectToRoute('home');
        }
    }



    /************************************Functions Ajax*****************************************************/

    /**
     * Permet de rechercher un client en ajax
     *
     * @Route("/client/search/{nom}", name="client_search")
     *
     * @param ClientRepository $repo
     * @param $nom
     * @return string
     */
    public function searchClient(ClientRepository $repo, $nom)
    {
        $clients = $repo->findBy(['nom'=>$nom]);

        foreach ($clients as $client){
           $clients =  $client->getNom();
        }

        return new Response($clients, Response::HTTP_OK);
    }

    /**
     * Permet de récupérer un client
     *
     * @Route("/client/{id}", name="client_show")
     *
     * @param Client $client
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function showClient(Client $client, SerializerInterface $serializer)
    {
        $client = $serializer->serialize($client, 'json', [
            'groups'=> ['fiche-client']
        ]);
        return new Response($client, Response::HTTP_OK);
    }


}

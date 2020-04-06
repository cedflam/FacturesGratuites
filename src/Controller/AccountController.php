<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use App\Services\TokenMailerService;
use App\Services\UploadImgService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;


class AccountController extends AbstractController
{
    /**
     * Permet de se connecter
     *
     * @Route("/login", name="account_login")
     *
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        //Permet d'afficher un message d'erreur en ca sde saisie erronnée
        $error = $utils->getLastAuthenticationError();
        //Conserve l'adresse mail an cas d'erreur
        $username = $utils->getLastUsername();
        //Affichage de la vue
        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se deconnecter
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {
        //géré par Symfony
    }


    /**
     * Permet de gérer une inscription
     *
     * @Route("/inscription", name="account_inscription")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param TokenGeneratorInterface $tokenGenerator
     * @param UploadImgService $upload
     * @param TokenMailerService $mailer
     * @return Response
     */
    public function register(Request $request,
                             EntityManagerInterface $manager,
                             UserPasswordEncoderInterface $encoder,
                             TokenGeneratorInterface $tokenGenerator,
                             UploadImgService $upload,
                             TokenMailerService $mailer)
    {

        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /******  Je traite l'upload de l'image  ********/

            //!!! Enlever le type String ainsi que le Self du getter et setter concerné
            //Je récupère le nom de l'image
            $file = $entreprise->getLogo();
            //Appel du service d'upload
            $fileName = $upload->uploadImg($entreprise, $file);
            //J'enregistre le nom de l'image cryptée en bdd
            $entreprise->setLogo($fileName);

            /************Je traite le password************/
            $password = $encoder->encodePassword($entreprise, $entreprise->getPassword());
            $entreprise->setPassword($password);
            $manager->persist($entreprise);


            /********Je traite le token*********/
            //Je génère le token
            $token = $tokenGenerator->generateToken();
            //Je capture une erreur éventuelle
            try {
                //Je stocke le token dans l'objet entreprise
                $entreprise->setToken($token);
                $manager->persist($entreprise);
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }

            //Appel du service d'envoi de token
            $mailer->sendToken($token, $entreprise->getEmail());
            //J'enregistre
            $manager->flush();

            $this->addFlash(
                'success',
                "L'inscription s'est bien passée, afin de valider votre compte un email vient de vous être adressé !"
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('account/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de valider une inscription
     *
     * @Route("/validated/{token}", name="account_valid")
     * @param $token
     * @param EntrepriseRepository $repo
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function validRegister($token, EntrepriseRepository $repo, EntityManagerInterface $manager)
    {
        //Je recupère le token
        $entreprise = $repo->findOneBy(['token' => $token]);
        //Condition
        if ($entreprise === null) {
            $this->addFlash('danger', "Token inconnu");
            return $this->redirectToRoute('home');
        }
        //Je supprime le token
        $entreprise->setToken('');
        $manager->persist($entreprise);
        $manager->flush();

        $this->addFlash('success', "Votre compte est validé ! Bienvenue sur FacturesGratuites ! Vous pouvez vous connecter !");

        return $this->render('home/index.html.twig');
    }

    /**
     * Fonction qui gère l'envoi d'un token à l'utilisateur par mail
     * lors de l'oubli de son mot de passe
     *
     * @Route("/forgot", name="account_forgot")
     *
     * @param Request $request
     * @param TokenMailerService $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @param EntityManagerInterface $manager
     * @param EntrepriseRepository $repo
     * @return Response
     */
    public function forgotPassword(
        Request $request,
        TokenMailerService $mailer,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $manager,
        EntrepriseRepository $repo
    )
    {
        //condition
        if ($request->isMethod('POST')) {

            //Je récupère l'email posté
            $email = $request->get('email');
            //Je vais chercher l'email de l'user avec l'email posté
            $entreprise = $repo->findOneBy(['email' => $email]);
            //Condition si l'email n'est pas trouvé
            if ($entreprise === null) {
                //Message flash
                $this->addFlash(
                    'danger',
                    "L'email saisi n'existe pas !"
                );
                //Redirection
                return $this->redirectToRoute('account_login');
            }

            //Je génère un token
            $token = $tokenGenerator->generateToken();
            //Je capture une erreur éventuelle
            try {
                //Je stocke le nouveau token dans l'objet User
                $entreprise->setToken($token);
                //Je persist
                $manager->persist($entreprise);
                //J'enregistre en bdd
                $manager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }

            //Appel du service d'envoi de token
            $mailer->sendForgotToken($token, $entreprise->getEmail());

            //Message flash
            $this->addFlash('success', "Un mail vient de vous être envoyé pour finaliser la procédure !");

            //Redirection
            return $this->redirectToRoute('home');
        }

        return $this->render('account/forgot_password.html.twig');
    }

    /**
     * Fonction qui permet de réinitialiser un mot de passe
     *
     * @Route("/reset_password/{token}", name="account_reset_password")
     *
     * @param Request $request
     * @param String $token
     * @param EntrepriseRepository $repo
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function resetPassword(
        Request $request,
        $token,
        EntrepriseRepository $repo,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder
    )
    {
        //Condition
        if ($request->isMethod('POST')) {

            //Je récupère le token
            $entreprise = $repo->findOneBy(['token' => $token]);

            //Si le token est vide
            if ($entreprise === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('home');
            }
            //Je supprime le token et j'encode le nouveau password
            $entreprise->setToken('')
                ->setPassword($encoder->encodePassword($entreprise, $request->request->get('password')));
            //Je persist
            $manager->persist($entreprise);
            //J'enregistre en bdd
            $manager->flush();

            //Message flash
            $this->addFlash('success', 'Mot de passe mis à jour, vous pouvez vous connecter !');
            //Redirection
            return $this->redirectToRoute('home');
        } else {
            //Affichage de la vue
            return $this->render('account/reset_password.html.twig', ['token' => $token]);
        }
        //Affichage de la vue
        return $this->render('account/reset_password.html.twig');
    }


    /**
     * Permet de modifier le compte utilisateur
     *
     * @Route("/account-edit", name="account_edit")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param UploadImgService $upload
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, UploadImgService $upload )
    {
        $entreprise =  $this->getUser();

        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /******  Je traite l'upload de l'image  ********/
            //!!! Enlever le type String ainsi que le Self du getter et setter concerné
            //Je récupère le nom de l'image
            $file = $entreprise->getLogo();
            //Appel du service d'upload
            $fileName = $upload->uploadImg($entreprise, $file);
            //J'enregistre le nom de l'image cryptée en bdd
            $entreprise->setLogo($fileName);

            /************Je traite le password************/
            $password = $encoder->encodePassword($entreprise, $entreprise->getPassword());
            $entreprise->setPassword($password);

            //J'enregistre
            $manager->persist($entreprise);
            $manager->flush();

            $this->addFlash(
                'success',
                "La modification s'est bien passée !"
            );

            return $this->redirectToRoute('home');

        }

        return $this->render('account/account_edit.html.twig',[
            'form'=> $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le dashboard
     *
     * @Route("/dashboard/{id}", name="dashboard")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === entreprise.getId() and user.getToken() === ''")
     *
     * @param Entreprise $entreprise
     * @return Response
     */
    public function dashbord(Entreprise $entreprise)
    {
        dump($this->getUser());
            return $this->render('account/dashbord.html.twig', [
                'entreprise'=> $entreprise
            ]);
    }

    /***********************Ajax************************************/

    /**
     * Permet de récupérer les information de l'entreprise en ajax
     *
     * @Route("/chart/{id}", name="entreprise_chart")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() === entreprise.getId() and user.getToken() === ''")
     *
     * @param Entreprise $entreprise
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function dataChart(Entreprise $entreprise, SerializerInterface $serializer)
    {
        $entreprise = $serializer->serialize($entreprise, 'json',[
            'groups'=> 'chart-entreprise'
        ]);
        return new Response($entreprise, Response::HTTP_OK);
    }
}

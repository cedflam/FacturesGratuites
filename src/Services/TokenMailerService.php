<?php

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TokenMailerService extends AbstractController
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * TokenMailerService constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Permet d'enoyer un token à l'inscription de l'utilisateur
     * @param $token
     * @param $email
     */
    public function sendToken($token, $email)
    {
        //Je génère l'url à envoyer à l'utilisateur
        $url = $this->generateUrl('account_valid', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
        //Jecrée le message
        $email = (new Email())
            ->from('cedflam@gmail.com')
            ->to($email)
            ->subject("Finalisation de votre inscription sur FacturesGratuites !")
            ->text("Bonjour, voici le lien permettant de finaliser votre inscription : \n" . $url);;
        //Envoi du message
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->addFlash(
                'danger',
                "Erreur lors de l'envoi du message: " . $e->getMessage()
            );
        }
    }

    /**
     * Permet d'envoyer un token lors de l'oubli du mot de passe
     * @param $token
     * @param $email
     */
    public function sendForgotToken($token, $email)
    {
        //Je génère l'url
        $url = $this->generateUrl('account_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
        //Je crée le message
        $email = (new Email())
            ->from('cedflam@gmail.com')
            ->to($email)
            ->subject("Réinitialisation de votre mot de passe sur FacturesGratuites !")
            ->text("Bonjour, voici le lien permettant de réinitialiser votre mot de passe :\n " . $url);
        //J'envoie le message
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->addFlash(
                'danger',
                "Erreur lors de l'envoi du message: " . $e->getMessage()
            );
        }
    }
}
<?php

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UploadImgService extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function uploadImg($entreprise, $file)
    {
        /******  Je traite l'upload de l'image  ********/

        //!!! Enlever le type String ainsi que le Self du getter et setter concerné
        //Si le champs est vide j'attribue une image par défaut
        if (empty($file)) {
            //Image par défaut
            $fileName = ('logoDefault.png');
        } else {

            //Je crypte le nom de l'image
            $fileName = $this->encoder->encodePassword($entreprise, $file) . '.' . $file->guessExtension();

            //Je capture une erreur éventuelle
            try {
                //Je déplace l'image dans le dossier
                //upload_directory est configuré dans config/services.yaml
                $file->move($this->getParameter('upload_directory'), $fileName);

            } catch (FileException $e) {
                $this->addFlash(
                    'danger',
                    "Une erreur s'est produite lors de l'upload de l'image : " . $e->getMessage()
                );
            }
        }
        return $fileName;
    }
}
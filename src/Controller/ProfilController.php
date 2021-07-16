<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="app_profil")
     */
    public function index(): Response
    {
        // Get the current user
        $user = $this->getUser();

        // Redirect if he does not exist
        if(!$user){
            $this->redirectToRoute('app_login');
        }

        return $this->render('profil/index.html.twig', [
            'user' => $user
        ]);
    }
}

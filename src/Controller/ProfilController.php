<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/profil")
 */
class ProfilController extends AbstractController
{
    /**
     * Profil page
     *
     * @Route("/", name="app_profil")
     */
    public function index(): Response
    {
        // Get the current user
        $user = $this->getUser();

        // Redirect to login page if the user does not exist
        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profil/index.html.twig', [
            'user' => $user
        ]);
    }

    public function update(): Response {

    }

    public function delete(): Response {

    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/profil")
 */
class ProfilController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->translator = $translator;
    }

    /**
     * Profil page
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

    /**
     * Edit a profil
     * @Route("/edit/{id}", name="app_profil_edit")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user = null): Response {
        // Redirect to login page if the user does not exist
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        // Create form
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // encode the  new plain password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Update the database
            $this->entityManager->flush();

            // FLash message for the success of edition profil
            $this->addFlash(
                'success',
                $this->translator->trans('user.messages.successEditProfil')
            );

            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/edit.html.twig', [
            'profilForm' => $form->createView()
        ]);
    }

    /**
     * Delete a profil
     * @Route("/delete/{id}", name="app_profil_delete", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request, User $user = null): Response {
        if(!$user) {
            $this->addFlash(
                'danger',
                $this->translator->trans('user.empty')
            );
            return $this->redirectToRoute('discipline_index');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                $this->translator->trans('user.messages.successDeleteProfil')
            );

            // Invalidate the session for the redirection
            $session = new Session();
            $session->invalidate();
//            $request->getSession()->invalidate(); Not working...
        }


        // Redirect home page after the deletion
        return $this->redirectToRoute('home');
    }
}

<?php


namespace App\Controller;


use App\Repository\CartRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/super_admin")
 */
class SuperAdminController extends AbstractController
{
    /**
     * Admin function for get All carts (buy or not)
     * @Route("/carts/all", name="admin_carts_all")
     */
    public function getAllCarts(CartRepository $cartRepository): Response
    {
        $carts = $cartRepository->findAll();
        return $this->render('admin/cart/all.html.twig', [
            'carts' => $carts,
        ]);
    }

    /**
     * Admin function for get unbought carts
     * @Route("/carts", name="admin_carts")
     */
    public function getUnboughtCarts(CartRepository $cartRepository): Response
    {
        $carts = $cartRepository->findByState(false);
        return $this->render('admin/cart/carts.html.twig', [
            'carts' => $carts,
        ]);

    }

    /**
     * Admin function for get all users
     * @Route("/users/all", name="admin_users_all")
     */
    public function getAllUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/user/all.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * Admin function for get the today Resgistered users
     * @Route("/users", name="admin_users")
     */
    public function getTodayRegisteredUsers(UserRepository $userRepository): Response
    {

        $users = $userRepository->findByCreatedAt();
        return $this->render('admin/user/users.html.twig', [
            'users' => $users
        ]);
    }
}
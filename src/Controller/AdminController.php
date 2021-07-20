<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/{_locale}/admin")
 */
class AdminController extends AbstractController
{
    /**
     * Admin home page
     * @Route("/home", name="admin_home")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
    /**
     * Admin index for managing products
     * @Route("/products", name="admin_products")
     */
    public function indexProducts(ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/products.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * Admin function for creating a product
     * @Route("/product/new", name="product_new", methods={"GET","POST"})
     */
    public function newProduct(Request $request, TranslatorInterface $t): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('success', $t->trans('product.added'));
                }

                $product->setImage($newFilename);
            }
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', $t->trans('product.added'));

            return $this->redirectToRoute('admin_products', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * Admin function for editing a product
     * @Route("/product/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function editProduct(Request $request, Product $product, TranslatorInterface $t): Response
    {
        $require = false;
        $form = $this->createForm(ProductType::class, $product, [
            'required' => $require
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $t->trans('product.updated'));

            return $this->redirectToRoute('admin_products', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * Admin function for deleting a product
     * @Route("/product/{id}", name="product_delete", methods={"POST"})
     */
    public function deleteProduct(Request $request, Product $product, TranslatorInterface $t): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('danger', $t->trans('product.delete'));
        }

        return $this->redirectToRoute('admin_products', [], Response::HTTP_SEE_OTHER);
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
}

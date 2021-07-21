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
     * Admin panel for managing products
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
     * Admin function for editing a product from admin panel
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
     * Edit a product from product detail page
     * @Route("/product/{id}/edit2", name="product_edit2", methods={"GET","POST"})
     */
    public function editProduct2(Request $request, Product $product, TranslatorInterface $t): Response
    {
        $require = false;
        $form = $this->createForm(ProductType::class, $product, [
            'required' => $require
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $t->trans('product.updated'));

            return $this->redirectToRoute('product_show', [
                'id' => $product->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/product/edit2.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * delete a product from the admin pannel
     * @Route("/product/{id}/delete", name="product_delete", methods={"POST"})
     */
    public function deleteProduct(Request $request, Product $product, TranslatorInterface $t): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('danger', $t->trans('product.deleted'));
        }

        return $this->redirectToRoute('admin_products', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * delete a product from product detail page
     * @Route("/product/{id}/delete2", name="product_delete2", methods={"POST"})
     */
    public function deleteProduct2(Request $request, Product $product, TranslatorInterface $t): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('danger', $t->trans('product.deleted'));
        }

        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }
}

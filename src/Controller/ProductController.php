<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * 
 * Get all products on customer side
 * @Route("/{_locale}")
 */
class ProductController extends AbstractController
{
    /**
     * List of products
     * @Route("/products", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }



    /**
     * Show detail from a product both side(admin and customer side)
     * @Route("/product/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product = null): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}

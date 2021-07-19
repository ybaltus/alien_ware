<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/{_locale}/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/products", name="admin_products")
     */
    public function indexProduct(ProductRepository $productRepository): Response
    {
        return $this->render('admin/products.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
}

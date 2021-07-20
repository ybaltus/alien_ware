<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * Homepage
     * @Route("/{_locale}/", name="home")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Retrieves 12 products ordered by name
        $products = $entityManager->getRepository(Product::class)->findLast12();

        return $this->render('home/index.html.twig', [

            'products' => $products
        ]);
    }

}

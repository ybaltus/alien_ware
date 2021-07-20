<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartContent;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("{_locale}/cart/content")
 */
class CartContentController extends AbstractController
{
    protected $session;

    /** @var ProductRepository */
    protected $productRepository;

    private $em;

    public function __construct(ProductRepository $productRepository, SessionInterface $session, EntityManagerInterface $em)
    {
        $this->productRepository = $productRepository;
        $this->session = $session;
        $this->em = $em;

    }

    /**
     * @Route("/", name="cart_content_insert")
     */
    public function insert(): Response
    {

        // création de l'entité Cart
        $cart = new Cart();
        $totalCart = 0;
        $cart->setUser($this->getUser());
        $cart->setPurchaseAt(new DateTimeImmutable('now'));
        $this->em->persist($cart);

        foreach ($this->session->get('cart', []) as $id => $qty) {
            $cartContent = new CartContent();
            $cartContent->setCart($cart);
            $cartContent->setCreateAt(new DateTimeImmutable('now'));
            dump($cartContent);
            $cartContent->setQuantity($qty);
            //On récupère chaque produit dans la Base de donnée
            $product = $this->productRepository->find($id);
            $cartContent->setProduct($product);
            // montant total du produit dans le CONTENU DU PANIER
            $cartContent->setTotal($cartContent->getQuantity() * $product->getPrice());
            $totalCart += $cartContent->getTotal();
            $this->em->persist($cartContent);
        }
        // Montant total du panier
        $cart->setTotal($totalCart);
        $this->em->flush();

        //Suppression du panier dans la session actuelle
        $this->session->remove('cart');

        return $this->redirectToRoute('cart_content_show', [
            'id' => $cart->getId()
        ]);
    }

    /**
     * @Route ("/{id}", name="cart_content_show", requirements={"id": "\d+"})
     */
    public function show(Cart $cart = null, CartRepository $cartRepository){

        $cart = $cartRepository->find($cart);

        return $this->render('cart_content/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route ("/validate/{id}", name="cart_content_validate", requirements={"id": "\d+"})
     */
    public function validate(Cart $cart = null, CartRepository $cartRepository){

        $cart = $cartRepository->find($cart);
        $cart->setState(true);
        $this->em->persist($cart);

        $this->addFlash("success", "FÉLICITATIONS, votre commande a bien été validé");

        $this->em->flush();

        return $this->render('cart_content/validate.html.twig', [
            'cart' => $cart,
        ]);
    }
}

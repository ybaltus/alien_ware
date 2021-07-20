<?php

namespace App\Controller;


use App\Entity\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/cart")
 */
class CartController extends AbstractController
{

    protected $session;

    /** @var ProductRepository  */
    protected $productRepository;

    public function __construct( ProductRepository $productRepository, SessionInterface $session){
        $this->productRepository = $productRepository;
        $this->session = $session;

    }

    /**
     * @Route("/add/{id}", name="cart_add", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function add($id, ProductRepository $productRepository, SessionInterface $session, Request $request): Response
    {
        // securisation : est ce que le produit existe bien
        $product = $productRepository->find($id);

        // si il n'exsiste on affiche un message d'erreur
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas");
        }

        // retrouver le panier dans la session sinon prendre un tableau vide
        $cart = $session->get('cart', []);

        // voir si le produit(id) existe dans le tableau

        // si il existe, on incremente la quantité du produit
        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } //sinon on ajoute le produit pour la 1ère fois
        else {
            $cart[$id] = 1;
        }

        //On définit l'attribut cart.
        $session->set('cart', $cart);

        // on affiche un message flash a l'utilisateur
        $this->addFlash('success', "L'article a bien été ajouter au panier !");

        // on redirige l'utilisateur à la page panier
        if ($request->query->get('returnToCart')){
            return  $this->redirectToRoute('cart_show');
        }

        // on redirige l'utilisateur à la page panier
        if ($request->query->get('returnToProduct')){
            return  $this->redirectToRoute('product_index');
        }

        // redirection de l'utilisateur dans la page show d'un produit
        return $this->redirectToRoute('product_show',
            [
                'id' => $id
            ]);
    }

    /**
     *@Route("/", name="cart_show")
     */
    public function show(SessionInterface $session, ProductRepository $productRepository)
    {
        // on initialise les variable total et detailCart dans un premier temps
        $detailedCart = [];
        $total = 0;

        $cart = new Cart();
        //
        foreach ($session->get('cart', []) as $id => $qty){
            //On récupère chaque produit dans la Base de donnée
            $product = $productRepository->find($id);
            //puis on les ajoute dans le tableau detailedCart
            $detailedCart[] = [
                'product' => $product,
                'qty' => $qty,

            ];
            // on dynamise le montant total du panier
            $total += $product->getPrice() * $qty;
        }
        // enfin on affiche tous dans la page ci-dessous
        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total,
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/delete/{id}", name="cart_delete", requirements={"id": "\d+"})
     */
    public function delete($id){
        // securisation : est ce que le produit existe bien
        $product = $this->productRepository->find($id);

        // si le produit n'exsiste on affiche un message d'erreur
        if (!$product){
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne pourra pas être supprimer");
        }

        // retrouver le panier dans la session sinon prendre un tableau vide
        $cart = $this->session->get('cart', []);

        // on vide le panier de la session
        unset($cart[$id]);

        //On définit l'attribut cart.
        $this->session->set('cart', $cart);

        // on affiche un message flash a l'utilisateur
        $this->addFlash("success", "Le produit a bien été supprimé du panier");

        // on redirige l'utilisateur dans la page panier
        return $this->redirectToRoute("cart_show");

    }

    /**
     * @Route("/decrement/{id}", name="cart_decrement", requirements={"id": "\d+"})
     */
    public function decrement($id){

        // securisation : est ce que le produit existe bien
        $product = $this->productRepository->find($id);

        // si le produit n'exsiste on affiche un message d'erreur
        if (!$product){
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne pourra pas être décrementer");
        }

        // retrouver le panier dans la session sinon prendre un tableau vide
        $cart = $this->session->get('cart', []);

        // si aucun produit n'existe on ne retourne rien
        if (!array_key_exists($id, $cart)){
            return false;
        }

        // on décremente la quantité du produit cart si il est supérieur à 1
        if ( $cart[$id] <= 1){
           return false;
        }else{
            $cart[$id]-- ;
        }


        //On définit l'attribut cart.
        $this->session->set('cart', $cart);

        // on affiche un message flash a l'utilisateur
        $this->addFlash("success", "Le produit a bien été mis à jour");

        // redirection de l'utilisateur dans la page panier
        return $this->redirectToRoute("cart_show");
    }
}

<?php

namespace App\Entity;

use App\Repository\CartContentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CartContentRepository::class)
 */
class CartContent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Quantity of the product
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\PositiveOrZero
     */
    private $quantity;

    /**
     * Date added in the cart
     * @ORM\Column(type="datetime_immutable")
     */
    private $createAt;

    /**
     * The product
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * The cart associated
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="cartContents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cart;

    /**
     * Total price for a quantity of product
     * @ORM\Column(type="float")
     */
    private $total;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }


}

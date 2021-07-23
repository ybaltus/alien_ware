<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Purchase date
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $purchaseAt;

    /**
     * State of cart (false=notPaid, true=Paid)
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $state = false;

    /**
     * User who created a cart
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="carts")
     */
    private $User;

    /**
     * Content of the cart ( products, quantities and dates)
     * @ORM\OneToMany(targetEntity=CartContent::class, mappedBy="cart", orphanRemoval=true)
     */
    private $cartContents;

    /**
     * Total price of the cart
     * @ORM\Column(type="float")
     */
    private $total;

    public function __construct()
    {
        $this->cartContents = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchaseAt(): ?\DateTimeImmutable
    {
        return $this->purchaseAt;
    }

    public function setPurchaseAt(\DateTimeImmutable $purchaseAt): self
    {
        $this->purchaseAt = $purchaseAt;

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection|CartContent[]
     */
    public function getCartContents(): Collection
    {
        return $this->cartContents;
    }

    public function addCartContent(CartContent $cartContent): self
    {
        if (!$this->cartContents->contains($cartContent)) {
            $this->cartContents[] = $cartContent;
            $cartContent->setCart($this);
        }

        return $this;
    }

    public function removeCartContent(CartContent $cartContent): self
    {
        if ($this->cartContents->removeElement($cartContent)) {
            // set the owning side to null (unless already changed)
            if ($cartContent->getCart() === $this) {
                $cartContent->setCart(null);
            }
        }

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
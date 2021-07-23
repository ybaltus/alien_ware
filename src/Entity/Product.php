<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name of the product
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Description of the product
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * Price of the product
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero
     */
    private $price;

    /**
     * Stock of the product
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private $stock;

    /**
     * Image of the product
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      max = 255,
     * )
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Event trigger when a product is deleted in order to delete the image file
     * @ORM\PostRemove
     */
    public function deleteImage()
    {
        if (!empty($this->image)) {
            $path = __DIR__ . '/../../public/uploads/images_products/' . $this->image;
            unlink($path);
        }
        return true;
    }

    public function __toString()
    {
        return $this->name;
    }
}

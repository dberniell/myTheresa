<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 */
class Cart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="cart")
     */
    private $items;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItem(Item $item): self
    {
        $this->items[] = $item;

        return $this;
    }
}

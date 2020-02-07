<?php
// src/Controller/CartItemsController.php
namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Item;
use App\Repository\CartRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CartItemsController
{
    /**
     * @Route("/addCartItem", name="add_item_cart", methods={"POST"})
     * @param Request $request
     * @param CartRepository $cartRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function addItemToCart(CartRepository $cartRepository, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $itemAdded = $this->persistItem($_POST['item'], $em, $cartRepository);

        return new Response(
            $serializer->serialize($itemAdded, 'json', [
                'circular_reference_handler' => function ($object) {
                    return ["id" => $object->getId()];
                }
            ])
        );
    }

    /**
     * @Route("/Cart/{cart}/items", name="get_cart_items", methods={"GET"})
     * @param $cart
     * @param CartRepository $cartRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function getCartItems($cart, CartRepository $cartRepository, SerializerInterface $serializer)
    {
        $cart = $cartRepository->find($cart);

        $cart = $serializer->serialize($cart, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);

        return new Response(
                $cart
        );
    }

    private function persistItem($data, EntityManagerInterface $em, CartRepository $cartRepository): Item
    {

        $item = Item::create($data);

        if (! $cart = $cartRepository->find($data['cart'])) {
            $cart = new Cart();
        }

        $cart->setItem($item);
        $em->persist($cart);

        $item->setCart($cart);
        $em->persist($item);

        $em->flush();

        return $item;
    }
}
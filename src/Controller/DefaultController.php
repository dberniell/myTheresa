<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\CartRepository;
use Doctrine\Common\Persistence\em;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param CartRepository $cartRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(CartRepository $cartRepository, EntityManagerInterface $em)
    {

        $formAddItem = $this->createForm(ItemType::class, null, [
            'action' => $this->generateUrl('add_item_cart'),
            'method' => 'POST'
        ])
            ->add('add', SubmitType::class);

        $carts = $cartRepository->findAll();

        $response = $this->render('home/home.html.twig', [
            'formAddItem' => $formAddItem->createView(),
            'carts' => $carts,
        ]);

        $response->headers->add(["Cache-Control" => "no-store, no-cache, must-revalidate"]);
        $response->headers->add(['Pragma' => 'no-cache']);
        $response->headers->add(['Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT']);

        return $response;
    }


}

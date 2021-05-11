<?php

namespace App\Controller;

use App\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/admin/index", name="admin.index")
     */
    public function adminIndex()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/farmer/index", name="farmer.index")
     */
    public function farmerIndex()
    {
        return $this->render('farmer/index.html.twig');
    }

    /**
     * @Route("/", name="user.index")
     */
    public function userIndex()
    {

        if($this->getUser() and !($this->getUser()->getCart()))
        {
            $cart = new Cart();
            $cart->setUuid();

            $user = $this->getUser();
            $user->setCart($cart);
            $cart->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($cart);
            $em->flush();
        }

        return $this->render('user/index.html.twig');
    }
}

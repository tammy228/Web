<?php

namespace App\Controller;

use App\Entity\ProductToCart;
use App\Entity\ProductToUserOrder;
use App\Entity\UserOrder;
use App\Repository\UserOrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /***
     *        ______
     *       / ____/____ _ _____ ____ ___   ___   _____
     *      / /_   / __ `// ___// __ `__ \ / _ \ / ___/
     *     / __/  / /_/ // /   / / / / / //  __// /
     *    /_/     \__,_//_/   /_/ /_/ /_/ \___//_/
     *
     */
    /**
     * @Route("/farmer/{uuid}/order", name="farmer.order.list")
     * @param UserRepository $userRepository
     * @param UserOrderRepository $userOrderRepository
     * @param $uuid
     * @return Response
     */
    public function farmerOrderList(UserRepository $userRepository, UserOrderRepository $userOrderRepository, $uuid){
        $farmer = $this->getUser();
        $orders = $farmer -> getFarmerOrders();
        return $this->render('farmer/order/list.html.twig',array(
            'orders'=>$orders,
        ));
    }

    /**
     * @Route("/farmer/{uuid}/order/fetch", name="farmer.order.fetch")
     * @param UserRepository $userRepository
     * @param UserOrderRepository $userOrderRepository
     * @param $uuid
     * @return Response
     */
    public function farmerOrderFetch(UserRepository $userRepository, UserOrderRepository $userOrderRepository, $uuid){
        $order = $userOrderRepository->findOneBy(array('uuid'=>$uuid));
        return $this->render('farmer/order/fetch.html.twig',array(
            'order'=>$order,
        ));
    }

    /**
     * @Route("/farmer/{uuid}/order/update", name="farmer.order.update")
     * @param UserRepository $userRepository
     * @param UserOrderRepository $userOrderRepository
     * @param $uuid
     * @return Response
     */
    public function farmerOrderUpdate(UserRepository $userRepository, UserOrderRepository $userOrderRepository, $uuid){
        $order = $userOrderRepository->findOneBy(array('uuid'=>$uuid));
        $status = $_POST['status'];
        $order->setStatus($status);
        $em=$this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();


        return $this->render('farmer/order/fetch.html.twig',array(
            'order'=>$order,
        ));
    }

}

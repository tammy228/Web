<?php

namespace App\Controller;

use App\Entity\ProductToCart;
use App\Entity\ProductToUserOrder;
use App\Entity\User;
use App\Entity\UserOrder;
use App\Repository\UserOrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /***
     *                  __            _
     *      ____ _ ____/ /____ ___   (_)____
     *     / __ `// __  // __ `__ \ / // __ \
     *    / /_/ // /_/ // / / / / // // / / /
     *    \__,_/ \__,_//_/ /_/ /_//_//_/ /_/
     *
     */
    /**
     * @Route("/admin/order", name="admin.order.list")
     * @return Response
     */
    public function adminOrderList(){
        $em = $this->getDoctrine()->getManager();
        $orderRepository = $em->getRepository(UserOrder::class);
        $orders = $orderRepository->findAll();

        return $this->render('admin/order/list.html.twig',array(
            'orders'=>$orders,
        ));
    }

    /**
     * @Route("/admin/order/{uuid}/info", name="admin.order.fetch")
     * @param UserOrderRepository $userOrderRepository
     * @param $uuid
     * @return Response
     */
    public function adminOrderFetch(UserOrderRepository $userOrderRepository, $uuid){
        $order = $userOrderRepository->findOneBy(array('uuid'=>$uuid));

        return $this->render('admin/order/fetch.html.twig',array(
            'order'=>$order,
        ));
    }

    /**
     * @Route("/admin/order/{uuid}/update", name="admin.order.update")
     * @param UserOrderRepository $userOrderRepository
     * @param $uuid
     * @return Response
     */
    public function adminOrderUpdate(UserOrderRepository $userOrderRepository, $uuid){
        $order = $userOrderRepository->findOneBy(array('uuid'=>$uuid));
        $status = $_POST['status'];
        $order->setStatus($status);
        $em=$this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        return $this->render('admin/order/fetch.html.twig',array(
            'order'=>$order,
        ));
    }


    /***
     *
     *      __  __ _____ ___   _____
     *     / / / // ___// _ \ / ___/
     *    / /_/ /(__  )/  __// /
     *    \__,_//____/ \___//_/
     *
     */

    /**
     * @Route("order", name="user.order.list")
     */
    public function userOrderList()
    {
        $user = $this->getUser();
        $orders = $user->getUserOrder();

        return $this->render("user/order/list.html.twig",array(
            "orders" => $orders
        ));
    }

}

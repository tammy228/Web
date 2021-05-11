<?php

namespace App\Controller;

use App\Entity\UserOrder;
use App\Entity\User;
use App\Repository\UserOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \SendGrid\Mail\Mail;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\OptimisticLockException;
use App\Repository\ProductRepository;
use Doctrine\DBAL\LockMode;


class OrderController extends AbstractController
{

    /**
     *     _      _       _
     *    /_\  __| |_ __ (_)_ _
     *   / _ \/ _` | '  \| | ' \
     *  /_/ \_\__,_|_|_|_|_|_||_|
     */


    /**
     * @Route("admin/order/{id}", name="admin.order.list", requirements={"id"="\d+"})
     */
    public function adminOrderList(UserOrderRepository $userOrderRepository, $id)
    {
        $users=[];
        if($id != 0){
            $orders = $userOrderRepository->findBy(array("status"=>$id),array("id"=>"DESC"));
        }
        else{
            $orders= $userOrderRepository->findAll();
        }
        foreach ($orders as $order)
        {
            $user = $order->getUser();
            if($user == Null)
            {
                $users[]='test';
            }
            else
            {
                $users[]=$user->getName();
            }
        }
        return $this->render('admin/order/list.html.twig',array(
            'orders'=>$orders,
            'users'=>$users,
        ));

    }

    /**
     * @Route("admin/order/{id}/fetch", name="admin.order.fetch", requirements={"id"="\d+"})
     */
    public function adminOrderFetch(UserOrderRepository $userOrderRepository, $id, ProductRepository $productRepository)
    {
        $category=[];
        $order = $userOrderRepository->find($id);
        $user = $order->getUser();
        if($user==null)
        {
            $user='test';
        }
        else
        {
            $user = $user->getName();
        }
        $productId=$order->getProductId();
        foreach ($productId as $item)
        {

            $product = $productRepository->find($item);
            if(!$product)
            {
                $category[]='此商品已被刪除';
                break;
            }
            $cate = $product->getCategory();
            $cateParent = $cate->getParent();
            while($cateParent)
            {
                $cate = $cateParent->getName().'->'.$cate->getName();
                $cateParent = $cateParent->getParent();
            }
            $category[]=$cate;
        }


        return $this->render('admin/order/fetch.html.twig',array(
            'order'=>$order,
            'user'=>$user,
            'category'=>$category,
        ));
    }

    /**
     * @Route("admin/order/{id}/update", name="admin.order.update", requirements={"id"="\d+"})
     *
     * @throws \SendGrid\Mail\TypeException
     */
    public function adminOrderUpdate(UserOrderRepository $userOrderRepository, $id)
    {
        $order = $userOrderRepository->find($id);
        $post = $_POST['status'];
        $status = $order->getStatus();
        if($post == $status)
        {
            return $this->redirectToRoute('admin.order.fetch',array('id'=>$id));
        }
        if($status==2)
        {
            $email= new Mail();
            $email->setFrom("shopping@example.com", "購物網");
            $email->setSubject('已出貨');
            $email->addTo($order->getUser()->getEmail(),'user');
            $email->addContent(
                "text/html", "您的訂單已出貨"
            );

            $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);

            $sendgrid->send($email);
        }
        elseif($status==3)
        {
            $email= new Mail();
            $email->setFrom("shopping@example.com", "購物網");
            $email->setSubject('已到貨');
            $email->addTo($order->getUser()->getEmail(),'user');
            $email->addContent(
                "text/html", "您的訂單已到貨"
            );

            $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);

            $sendgrid->send($email);
        }
        $status=$status+1;
        $order->setStatus($status);
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        return $this->redirectToRoute('admin.order.fetch',array('id'=>$id));

    }

    /**
     * @Route("admin/order/{id}/cancel",name="admin.order.cancel", requirements={"id"="\d+"})
     */
    public function adminOrderCancel(UserOrderRepository $userOrderRepository, $id)
    {
        $order = $userOrderRepository->find($id);

        $order->setStatus(6);
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        return $this->redirectToRoute('admin.order.fetch',array('id'=>$id));

    }

    /**
     *     _      _   _
     *    /_\  __| |_(_)___ _ _  ___
     *   / _ \/ _|  _| / _ \ ' \(_-<
     *  /_/ \_\__|\__|_\___/_||_/__/
     */

    /**
     * @Route("order", name="order.list")
     */
    public function orderList(UserOrderRepository $userOrderRepository)
    {
        $user = $this->getUser();

        $orders = $userOrderRepository->findBy(array('user'=>$user));

        return $this->render('order/list.html.twig',array(
            'orders'=>$orders,
        ));

    }

    /**
     * @Route("order/{id}/fetch", name="order.fetch", requirements={"id"="\d+"})
     */
    public function orderFetch(UserOrderRepository $userOrderRepository, $id)
    {
        return $this->render('order/fetch.html.twig',array(
            'order'=>$userOrderRepository->find($id),
        ));
    }
}

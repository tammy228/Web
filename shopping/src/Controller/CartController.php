<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    /**
     * @Route("/cart/put/{id}", name = "cart.put", requirements={"id"="\d+"})
     */
    public function newItem($id, ProductRepository $productRepository)
    {
        if(!$this->getUser())
        {
            echo "<script>alert('請先登入');window.location = 'http://localhost:8003/login/';</script>";
            return true;
        }

        if($this->getUser()->getEmailVerified() == false)
        {
            echo "<script>alert('請先驗證信箱');window.location = 'http://localhost:8003/verify/';</script>";
            return true;
        }
        $is_existed = false;
        $product = $productRepository->find($id);
        $result_explode = explode(',', $_POST['format']);
        if((int)$_POST['quantity']>$product->getStock()[$result_explode[2]])
        {
            return $this->redirectToRoute('product.fetch', array(
                'id' => $id,
            ));
        }
        if(isset($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $i=>$value){
                if($id ==$_SESSION['cart'][$i]['productId'] and $result_explode[0] == $_SESSION['cart'][$i]['format'])
                {
                    $is_existed = true;
                    break;
                }
            }
        }

        if($is_existed == false)
        {
            if($product->getImage() != null)
            {
                $temp['image']=$product->getImage()[0];
            }
            else
            {
                $temp['image']=null;
            }
            $temp['productId'] = $id;
            $temp['name'] = $product->getName();
            $temp['format'] = $result_explode[0];
            $temp['price'] = (int)$result_explode[1];
            $temp['quantity'] = (int)$_POST['quantity'];
            $temp['subTotal'] = $temp['price'] * $temp['quantity'];

            $_SESSION['cart'][] = $temp;
        }
        else
        {
            echo "<script>alert('重複商品');window.history.back(-1);</script>";
            return true;
        }
        echo "<script>alert('成功加入購物車');window.history.back(-1);</script>";
        return true;
    }


    /**
     * @Route("/cart", name="cart.list")
     */
    public function cartList()
    {
        if(!$this->getUser())
        {
            echo "<script>alert('請先登入');window.location = 'http://localhost:8003/login/';</script>";
            return true;
        }

        if($this->getUser()->getEmailVerified() == false)
        {
            echo "<script>alert('請先驗證信箱');window.location = 'http://localhost:8003/verify/';</script>";
            return true;
        }

        if(isset($_SESSION['cart']))
        {
            return $this->render('cart/list.html.twig',array(
                'carts' => $_SESSION['cart']
            ));
        }
        else{
            return $this->render('cart/list.html.twig',array(
                'carts' => null
            ));
        }
    }

    /**
     * @Route("/cart/{id}/update", name="cart.update", requirements={"id"="\d+"})
     */
    public function cartUpdate($id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);

        $format = $product->getFormat();
        $stock = $product->getStock();

        foreach ($format as $key => $value)
        {
            if($value == $_POST['newFormat'])
            {

                if($stock[$key]<$_POST['newQuantity'])
                {
                    echo "<script>alert('超過庫存數');window.history.back(-1);</script>";
                }
                else
                {
                    $_SESSION['cart'][$_POST['key']]['quantity']=$_POST['newQuantity'];
                    $_SESSION['cart'][$_POST['key']]['subTotal']=$_POST['newQuantity'] * $_SESSION['cart'][$_POST['key']]['price'];
                    return $this->redirectToRoute('cart.list');
                }
            }
            echo "<script>alert('未找到產品');window.history.back(-1);</script>";
        }
    }

    /**
     * @Route("/cart/{id}/delete", name="cart.delete", requirements={"id"="\d+"})
     */
    public function cartDelete($id)
    {

        unset($_SESSION['cart'][$id]);
        sort($_SESSION['cart']);

        return $this->redirectToRoute('cart.list');
    }



}
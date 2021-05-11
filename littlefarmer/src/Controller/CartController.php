<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\ProductToCart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    /***
     *                    __   _
     *      ____ _ _____ / /_ (_)____   ____
     *     / __ `// ___// __// // __ \ / __ \
     *    / /_/ // /__ / /_ / // /_/ // / / /
     *    \__,_/ \___/ \__//_/ \____//_/ /_/
     *
     */

    /**
     * @Route("/cart", name="cart.list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCartItem(Request $request)
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute("user.index");
        }
        $cart = $this->getUser()->getCart();
        $em = $this->getDoctrine()->getManager();

        $productToCartRepo = $em->getRepository(ProductToCart::class);
        $relations = $productToCartRepo->findBy(array("cart" => $cart));

        return $this -> render("user/cart/list-item.html.twig", array(
            "relations" => $relations,
        ));

    }

    /**
     * @Route("/cart/item/{id}/add", name="cart.item.add", requirements={"id"="\d+"})
     * @param $id
     * @return RedirectResponse
     */
    public function addCartItem($id)
    {
        if(!$this->getUser())
            return $this->redirectToRoute("auth.login");

        $em = $this->getDoctrine()->getManager();
        $productToCartRepo = $em->getRepository(ProductToCart::class);

        $cart = $this->getUser()->getCart();
        $product = $em->getRepository(Product::class)->find($id);

        if($_POST["quantity"] > $product->getStock()){
            echo "<script>alert('超過庫存數');window.history.back(-1);</script>";
            die;
        }


        $relation = $productToCartRepo->findOneBy(array(
           "cart" => $cart,
           "product" => $product
        ));

        if($relation)
        {
            $quantity = $relation->getQuantity();
            $relation->setQuantity($_POST["quantity"] + $quantity);
            $em->persist($relation);
            $em->flush();
        }else{
            $productToCart = new ProductToCart();
            $productToCart->setCart($cart);
            $productToCart->setProduct($product);
            $productToCart->setQuantity($_POST["quantity"]);

            $em->persist($productToCart);
            $em->flush();
        }

        return $this->redirectToRoute("product.fetch",array(
            "uuid" => $product->getUuid()
        ));
    }

    /**
     * @Route("/cart/update", name="cart.update")
     */
    public function updateCart()
    {

        $cart = $this->getUser()->getCart();
        $em = $this->getDoctrine()->getManager();
        $productToCartRepo = $em->getRepository(ProductToCart::class);
        $relations = $productToCartRepo->findBy(array("cart" => $cart));
        $firstFarmerId = $relations[0]->getProduct()->getUser()->getId();

        foreach ($relations as $relation)
        {
            $name = "newQuantity-".$relation->getProduct()->getId();
            $newQuantity = $_POST[$name];

            //判斷user輸入的數量有無大於庫存
            $stock = $relation->getProduct()->getStock();
            $productName = $relation->getProduct()->getZhName();
            if($newQuantity > $stock) {
                echo "<script>alert('$productName 超過庫存數  剩餘$stock 組');window.history.back(-1);</script>";
                die;
            }
            if($firstFarmerId != $relation->getProduct()->getUser()->getId()){
                echo "<script>alert('無法結帳不同小農的產品');window.history.back(-1);</script>";
                die;
            }

            $relation->setQuantity($newQuantity);
            $em->persist($relation);
        }
        $em->flush();


        return $this->redirectToRoute("ecpay.prepare");
    }
    /**
     * @Route("/cart/item/{id}/delete", name="cart.item.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCartItem(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $cart = $this->getUser()->getCart();
        $product = $em->getRepository(Product::class)->find($id);
        $productToCartRepo = $em->getRepository(ProductToCart::class);

        $relation = $productToCartRepo->findOneBy(array(
            "cart" => $cart,
            "product" => $product
        ));

        $em->remove($relation);
        $em->flush();

        return $this->redirectToRoute("cart.list");
    }

}

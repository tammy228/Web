<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\ProductToCart;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if(!$user)
        {
            echo "<script>alert('請先登入');window.history.back(-1);</script>";
            return $this->redirectToRoute("user.index");
        }
        if(!$user->isEmailValidated())
        {
            $userVerifyUrl = $this->generateUrl("user.verify",[],UrlGeneratorInterface::ABSOLUTE_URL);
            echo "<script>alert('尚未驗證，將為您轉至驗證頁面');window.location.assign(\"$userVerifyUrl\");</script>";
            die;
        }

        $cart = $this->getUser()->getCart();
        $em = $this->getDoctrine()->getManager();

        $productToCartRepo = $em->getRepository(ProductToCart::class);
        $relations = $productToCartRepo->findBy(array("cart" => $cart));

        if(!$relations) {
            $userIndexUrl = $this->generateUrl("user.index",[],UrlGeneratorInterface::ABSOLUTE_URL);
            echo "<script>alert('尚未購物，將為您轉至首頁');window.location.assign(\"$userIndexUrl\");</script>";
            die;
        }
        return $this -> render("user/cart/list-item.html.twig", array(
            "relations" => $relations,
        ));

    }

    /**
     * @Route("/cart/item/{id}/add", name="cart.item.add", requirements={"id"="\d+"})
     * @param $id
     * @return JsonResponse
     */
    public function addCartItem($id)
    {
//        if(!$this->getUser()){
//            echo "<script>alert('請先登入');window.history.back(-1);</script>";
//            die;
//        }
        $em = $this->getDoctrine()->getManager();
        $productToCartRepo = $em->getRepository(ProductToCart::class);

        $cart = $this->getUser()->getCart();
        $product = $em->getRepository(Product::class)->find($id);
        $sizeArray = $product->getSize();
        $priceArray = $product->getPrice();
        $stockArray = $product->getStock();


        if(!($_POST))
        {
            $_POST["quantity"] = 1;
            $_POST["size"] = 0;
        }

        if($_POST["quantity"] > $stockArray[$_POST['size']]){
            echo "<script>alert('超過庫存數');window.history.back(-1);</script>";
            die;
        }

        if($_POST["quantity"] == NULL){
            echo "<script>alert('請選擇數量');window.history.back(-1);</script>";
            die;
        }


        $relation = $productToCartRepo->findOneBy(array(
           "cart" => $cart,
           "product" => $product,
           "size" => $sizeArray[$_POST['size']]
        ));

        //檢查user 是不是又再買
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
            $productToCart->setSize($sizeArray[$_POST["size"]]);
            $productToCart->setPrice($priceArray[$_POST["size"]]);

            $em->persist($productToCart);
            $em->flush();
        }

        return new JsonResponse('',200);
    }

    /**
     * @Route("/cart/update", name="cart.update")
     */
    public function updateCart()
    {
        $em = $this->getDoctrine()->getManager();
        $cart = $this->getUser()->getCart();
        $productToCartRepo = $em->getRepository(ProductToCart::class);
        $relations = $productToCartRepo->findBy(array("cart" => $cart));

        $count = 0;
        $total = 0;
        foreach ($relations as $relation)
        {
            $sizeArray = $relation->getProduct()->getSize();
            $index = array_search($relation->getSize(), $sizeArray);

            $productId = "newQuantity-".$relation->getProduct()->getId();
            $name = $productId."/".$index;
            $newQuantity = $_POST[$name];

            //判斷user輸入的數量有無大於庫存
            $stockArray = $relation->getProduct()->getStock();
            $stock = $stockArray[$index];
            $productName = $relation->getProduct()->getZhName();
            if($newQuantity > $stock) {
                echo "<script>alert('$productName 超過庫存數  剩餘$stock 組');window.history.back(-1);</script>";
                die;
            }

            $relation->setQuantity($newQuantity);
            $em->persist($relation);
            //計算8個出貨
            $count = $count + $relation->getQuantity();
            $total = $total + $relation->getQuantity()* $relation->getPrice();
        }
//        if(!$cart->getTotalPrice())
            $cart->setTotalPrice($total+60);
        $em->persist($cart);

        $em->flush();

        if($count < 8){
            echo "<script>alert('未達出貨標準');window.history.back(-1);</script>";
            die;
        }



        return $this->redirectToRoute("ecpay.prepare");
    }

    /**
     * @Route("/cart/item/{id}/delete", name="cart.item.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteCartItem(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $cart = $this->getUser()->getCart();
        $product = $em->getRepository(Product::class)->find($id);
        $productToCartRepo = $em->getRepository(ProductToCart::class);
        $relation = $productToCartRepo->find($id);


        $em->remove($relation);
        $em->flush();

        return new RedirectResponse($request->headers->get('Referer') ?? $this->generateUrl("user.index"));
    }

    /**
     * @Route("/cart/coupon", name="cart.use.coupon")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function cartCoupon(Request $request)
    {
        $total = $_POST['total'];
        $message = "";

        $em = $this->getDoctrine()->getManager();
        $couponRepository = $em->getRepository(Coupon::class);
        $coupon = $couponRepository->findOneBy(['code'=>$_POST['coupon']]);

        $cartRepository = $em->getRepository(Cart::class);
        $productToCartRepo = $em->getRepository(ProductToCart::class);
        $cart = $cartRepository->findOneBy(['uuid'=>$_POST['car']]);
        $relations = $productToCartRepo->findBy(array("cart" => $cart));

        if(!$coupon){
            $cart->setTotalPrice(round($total)+60);
            $cart->setCouponMessage($message);
            $em->persist($cart);
            $em->flush();
            return new JsonResponse(['status'=> true, 'message'=>'無此優惠碼', 'total'=>$total+60]);
        }

        if($coupon->getExpireAt() < new \DateTime('now + 8hours')) {
            $cart->setTotalPrice(round($total) + 60);
            $cart->setCouponMessage($message);
            $em->persist($cart);
            $em->flush();
            return new JsonResponse(['status' => true, 'message' => '此優惠碼已過期', 'total' => $total + 60]);
        }


        if(!$relations) {
            $cart->setTotalPrice(round($total) + 60);
            $cart->setCouponMessage($message);
            $em->persist($cart);
            $em->flush();
            return new JsonResponse(['status' => true, 'message' => '請先選購商品', 'total' => $total + 60]);
        }

        //一切正常情況下開始計算優惠過後的價錢
        if($coupon->getTarget() == 1)
        {
            if($coupon->getType() == 0)
            {
                $total = $total * $coupon->getNumber() / 100;
                $message = '整批單乘'.$coupon->getNumber().'%';
            }
            elseif($coupon->getType() == 1)
            {
                $total = $total - $coupon->getNumber();
                $message = '整批單扣'.$coupon->getNumber().'元';
            }
            elseif($coupon->getType() == 2)
            {
                $total = $coupon->getNumber();
                $message = '整批單為'.$coupon->getNumber().'元';
            }
        }
        elseif($coupon->getTarget() == 0)
        {
            if($coupon->getType() == 0)
            {
                $total = 0;
                foreach ($relations as $relation)
                {
                    $total = $total + ($relation->getQuantity() * $relation->getPrice() * $coupon->getNumber() / 100);
                }
                $message = '每件商品乘'.$coupon->getNumber().'%';

            }
            elseif($coupon->getType() == 1)
            {
                $total = 0;
                foreach ($relations as $relation)
                {
                    $total = $total + (($relation->getPrice() - $coupon->getNumber()) * $relation->getQuantity());
                }
                $message = '每件商品扣'.$coupon->getNumber().'元';
            }
            elseif($coupon->getType() == 2)
            {
                $total = $coupon->getNumber()*count($relations);
                $message = '每件商品為'.$coupon->getNumber().'元';
            }
        }


        $cart->setTotalPrice(round($total)+60);
        $cart->setCouponMessage($message);
        $em->persist($cart);
        $em->flush();

        return new JsonResponse(['status '=> true, 'message' => $message, 'total'=> round($total)+60]);
    }

}

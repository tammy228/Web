<?php

namespace App\Controller;

use App\ECPay\sdk\payment;
use App\ECPay\sdk\payment\ECPay_AllInOne;
use App\ECPay\sdk\payment\ECPay_CheckMacValue;
use App\ECPay\sdk\payment\ECPay_EncryptType;
use App\Entity\Product;
use App\Entity\ProductToCart;
use App\Entity\ProductToUserOrder;
use App\Entity\UserOrder;
use App\Repository\ProductRepository;
use App\Repository\UserOrderRepository;
use http\Exception;
use SendGrid\Mail\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ECPayController extends AbstractController
{
    /**
     * @Route("/prepare", name="ecpay.prepare")
     */
    public function prepare()
    {
        return $this->render("user/ecpay/prepare.html.twig");
    }

    /**
     * @Route("/pay", name="ECPay")
     * @param ProductRepository $productRepository
     * @return Response
     * @throws \SendGrid\Mail\TypeException
     * @throws \Exception
     */
    function pay(ProductRepository $productRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $cart = $this->getUser()->getCart();
        $productToCartRepo = $em->getRepository(ProductToCart::class);
        $relations = $productToCartRepo->findBy(array("cart" => $cart));
        $farmer = $relations[0]->getProduct()->getUser();

        /**
         * Ecpay
         */
        $MerchantTradeNo = substr(sha1(uniqid('attempt_')), 0, 20);


        /**
         * 建立訂單
         */
        $order = new UserOrder();
        $order->setUuid();
        $order->setStatus(0);
        $order->setDelivery($_POST['delivery']);
        $order->setRemark($_POST['remark']);
        $order->setPayment($_POST['ChoosePayment']);
        $order->setMerchantTradeNo($MerchantTradeNo);
        $order->setRecipientName($_POST['name']);
        $order->setRecipientMobile($_POST['mobile']);
        $order->setRecipientEmail($_POST['email']);
        $order->setRecipientAddress($_POST['county'].$_POST['district'].$_POST['street']);
        $order->setUser($this->getUser());
        $order->setFarmer($farmer);
        $em->persist($order);

        /**
         * 建立訂單後傳送信件
         */
        $email= new Mail();
        $email->setFrom("no-reply@littlefarmer.com", "小農點點");
        $email->setSubject('已下單');
        $email->addTo($this->getUser()->getEmail(),'user');
        $email->addContent(
            "text/html", "感謝您的下單"
        );

        $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);
        $sendgrid->send($email);


        $total = 0;
        $name = "";
        foreach ($relations as $relation)
        {
            $product = $relation->getProduct();

            //計算ecpay 需要的資料
            $total += $product->getPrice() * $relation->getQuantity();
            $name = $name.$product->getZhName()."x".$relation->getQuantity()."#";

        }
        $em->flush();

        /**
         * ECPay
         */
        $ecpay = new ECPay_AllInOne();
        $arParameters = $_POST;

        //服務參數
//        $ecpay->ServiceURL = "https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5";
//        try{
//            $ecpay->HashKey = $farmer->getHashKey();
//            $ecpay->HashIV = $farmer->getHashIV();
//            $ecpay->MerchantID = $farmer->getMerchantID();
//        } catch (Exception $e) {
//            //TODO: Exception not determined
//            return true;
//        }

        $ecpay->ServiceURL  = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';
        $ecpay->HashKey     = '5294y06JbISpM5x9';
        $ecpay->HashIV      = 'v77hoKGq4kWxNNIS';
        $ecpay->MerchantID  = 2000132;
        $ecpay->EncryptType = ECPay_EncryptType::ENC_SHA256;
        $ecpay->CheckMacValue = ECPay_CheckMacValue::generate( $arParameters, '5294y06JbISpM5x9', 'v77hoKGq4kWxNNIS' );

//        $ecpay->CheckMacValue = ECPay_CheckMacValue::generate( $arParameters, $farmer->getHashKey(), $farmer->getHashIV() );

        $date = new \DateTime("now + 8 hours");
        $ecpay->Send['MerchantTradeNo'] = $MerchantTradeNo;                           //訂單編號
        $ecpay->Send['MerchantTradeDate'] = $date->format("Y/m/d H:i:s");
        $ecpay->Send['PaymentType']  = "aio";
        $ecpay->Send['TotalAmount'] = $total;
        $ecpay->Send['TradeDesc'] = "購買清單";
        $ecpay->Send['ReturnURL'] = $this->generateUrl("ecpay.callback",[],UrlGeneratorInterface::ABSOLUTE_URL);
        $ecpay->Send['ChoosePayment'] = $_POST['ChoosePayment'];
        $ecpay->Send['OrderResultURL'] = $this->generateUrl("ecpay.completed",[],UrlGeneratorInterface::ABSOLUTE_URL);

        //訂單的商品資料
        array_push($ecpay->Send['Items'], array(
                'Name' => $name,
                'Price' => $total,
                'Currency' => "元",
                'Quantity' => (int)1
            )
        );

        //產生訂單(auto submit至ECPay)
        $Response = $ecpay->CheckOut();
        return new Response($Response, Response::HTTP_ACCEPTED, array(
            "Content-Type" => "text/html"
        ));

    }

    /**
     * @Route("/pay/completed" , name="ecpay.completed")
     * @param UserOrderRepository $userOrderRepository
     * @return Response
     * @throws \SendGrid\Mail\TypeException
     */
    public function ECPayCompleted(UserOrderRepository $userOrderRepository)
    {
        $arParameters = $_POST;
        $em = $this->getDoctrine()->getManager();

        /**
         * @var UserOrder $order
         */
        $order = $userOrderRepository->findOneBy(array("merchantTradeNo"=>$arParameters['MerchantTradeNo']));
        $order->setStatus(2);
        $em->persist($order);

        $cart = $order->getUser()->getCart();
        $productToCartRepo = $em->getRepository(ProductToCart::class);
        $relations = $productToCartRepo->findBy(array("cart" => $cart));


        foreach ($relations as $relation)
        {
            /**
             * @var Product $product
             */
            $product = $relation->getProduct();

            //建立訂單與商品的關聯
            $productToUserOrder = new ProductToUserOrder();
            $productToUserOrder->setUserOrder($order);
            $productToUserOrder->setProduct($product);
            $productToUserOrder->setQuantity($relation->getQuantity());
            $em->persist($productToUserOrder);

            //處理庫存
            $stock = $product->getStock();
            $stock = $stock - $relation->getQuantity();
            $product->setStock($stock);
            $em->persist($product);

            //清空 productToCart
            $em->remove($relation);
        }
        $em->flush();

        $email= new Mail();
        $email->setFrom("no-reply@littlefarmer.com", "小農點點");
        $email->setSubject('訂單處理中');
        $email->addTo($order->getUser()->getEmail(),'user');
        $email->addContent(
            "text/html", "您的訂單正在處理中"
        );

        $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);

        $sendgrid->send($email);
        return $this->render("/user/ecpay/complete-payment.html.twig");
    }

    /**
     * @Route("/pay/callback", name="ecpay.callback")
     */
    public function ECPayCallback(UserOrderRepository $userOrderRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $cart = $this->getUser()->getCart();
        $productToCartRepo = $em->getRepository(ProductToCart::class);
        $relations = $productToCartRepo->findBy(array("cart" => $cart));
        $farmer = $relations[0]->getProduct()->getUser();

        $arParameters = $_POST;

        // 計算出 CheckMacValue
//        $CheckMacValue = ECPay_CheckMacValue::generate( $arParameters, $farmer->getHashKey(), $farmer->getHashIV() );
        $CheckMacValue = ECPay_CheckMacValue::generate( $arParameters, '5294y06JbISpM5x9', 'v77hoKGq4kWxNNIS' );


        // 必須要支付成功並且驗證碼正確
        if ( $_POST['RtnCode'] =='1' or $CheckMacValue == $_POST['CheckMacValue'] )
        {
//            /**
//             * @var UserOrder $order
//             */
//            $order = $userOrderRepository->findBy(array("merchantTradeNo"=>$arParameters['MerchantTradeNo']));
//            $order->setStatus(2);
//            $em->persist($order);
//
//            foreach ($relations as $relation)
//            {
//                $product = $relation->getProduct();
//
//                //建立訂單與商品的關聯
//                $productToUserOrder = new ProductToUserOrder();
//                $productToUserOrder->setUserOrder($order);
//                $productToUserOrder->setProduct($product);
//                $productToUserOrder->setQuantity($relation->getQuantity());
//                $em->persist($productToUserOrder);
//
//                //處理庫存
//                $stock = $product->getStock();
//                $product->setStock($stock - $relation->getQuantity());
//                $em->persist($product);
//
//                //清空 productToCart
//                $em->remove($relation);
//            }
//            $em->flush();
//
//            $email= new Mail();
//            $email->setFrom("no-reply@littlefarmer.com", "小農點點");
//            $email->setSubject('訂單處理中');
//            $email->addTo($this->getUser()->getEmail(),'user');
//            $email->addContent(
//                "text/html", "您的訂單正在處理中"
//            );
//
//            $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);
//
//            $sendgrid->send($email);
        }else{
            /**
             * @var UserOrder $order
             */
            $order = $userOrderRepository->findBy(array("merchantTradeNo"=>$arParameters['MerchantTradeNo']));
            $order->setPayment("付款失敗");
            $em->persist($order);

            $email= new Mail();
            $email->setFrom("no-reply@littlefarmer.com", "小農點點");
            $email->setSubject('付款失敗');
            $email->addTo($this->getUser()->getEmail(),'user');
            $email->addContent(
                "text/html", "您的付款失敗，請洽店家謝謝"
            );

            $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);

            $sendgrid->send($email);
        }

        // 接收到資訊回應綠界
        echo '1|OK';
    }
//    /**
//     * @Route("callback/ecpay", name="callback.ecpay", methods={"POST"})
//     */
//    public function callbackECPay(UserOrderRepository $userOrderRepository)
//    {
//        $arParameters = $_POST;
//        foreach ($arParameters as $keys => $value) {
//            if ($keys != 'CheckMacValue') {
//                if ($keys == 'PaymentType') {
//                    $value = str_replace('_CVS', '', $value);
//                    $value = str_replace('_BARCODE', '', $value);
//                    $value = str_replace('_CreditCard', '', $value);
//                }
//                if ($keys == 'PeriodType') {
//                    $value = str_replace('Y', 'Year', $value);
//                    $value = str_replace('M', 'Month', $value);
//                    $value = str_replace('D', 'Day', $value);
//                }
//                $arFeedback[$keys] = $value;
//            }
//        }
//
//        $em = $this->getDoctrine()->getManager();
//        $cart = $this->getUser()->getCart();
//        $productToCartRepo = $em->getRepository(ProductToCart::class);
//        $relations = $productToCartRepo->findBy(array("cart" => $cart));
//        $farmer = $relations[0]->getProduct()->getUser();
//
//        // 計算出 CheckMacValue
//        $CheckMacValue = ECPay_CheckMacValue::generate( $arParameters, '5294y06JbISpM5x9', 'v77hoKGq4kWxNNIS' );
////        $CheckMacValue = ECPay_CheckMacValue::generate( $arParameters, $farmer->getHashKey(), $farmer->getHashIV() );
//
//
//        // 必須要支付成功並且驗證碼正確
//        if ( $_POST['RtnCode'] =='1' or $CheckMacValue == $_POST['CheckMacValue'] )
//        {
//
//            /**
//             * 建立訂單
//             */
//            $order = new UserOrder();
//            $order->setUuid();
//            $order->setStatus(0);
//            $order->setDelivery($_POST['delivery']);
//            $order->setRemark($_POST['remark']);
//            $order->setPayment($_POST['ChoosePayment']);
//            $order->setRecipientName($_POST['name']);
//            $order->setRecipientMobile($_POST['mobile']);
//            $order->setRecipientEmail($_POST['email']);
//            $order->setRecipientAddress($_POST['county'].$_POST['district'].$_POST['street']);
//            $order->setUser($this->getUser());
//            $order->setFarmer($farmer);
//            $em->persist($order);
//
//            foreach ($relations as $relation)
//            {
//                $product = $relation->getProduct();
//                //建立訂單與商品的關聯
//                $productToUserOrder = new ProductToUserOrder();
//                $productToUserOrder->setUserOrder($order);
//                $productToUserOrder->setProduct($product);
//                $productToUserOrder->setQuantity($relation->getQuantity());
//                $em->persist($productToUserOrder);
//
//                //處理庫存
//                $stock = $product->getStock();
//                $product->setStock($stock - $relation->getQuantity());
//                $em->persist($product);
//
//                //清空 productToCart
//                $em->remove($relation);
//            }
//            $em->flush();
//
////            $order = $userOrderRepository->findBy(array("TradeNo"=>$arParameters['MerchantTradeNo']));
////            $order->setStatus(2);
////
////            $em = $this->getDoctrine()->getManager();
////            $em->persist($order);
////            $em->flush();
//
//            $email= new Mail();
//            $email->setFrom("shopping@example.com", "購物網");
//            $email->setSubject('訂單處理中');
//            $email->addTo($this->getUser()->getEmail(),'user');
//            $email->addContent(
//                "text/html", "您的訂單正在處理中"
//            );
//
//            $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);
//
//            $sendgrid->send($email);
//        }
//
//        // 接收到資訊回應綠界
//        echo '1|OK';
//    }
}

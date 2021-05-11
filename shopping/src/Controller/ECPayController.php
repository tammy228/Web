<?php

namespace App\Controller;


use App\Entity\UserOrder;
use Doctrine\DBAL\Driver\Connection;
use App\ECPay\sdk\payment\ECPay_AllInOne;
use App\ECPay\sdk\payment\ECPay_CheckMacValue;
use App\ECPay\sdk\payment\ECPay_PaymentMethod;
use Doctrine\ORM\OptimisticLockException;
use App\Repository\ProductRepository;
use App\Repository\UserOrderRepository;
use Doctrine\DBAL\LockMode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \SendGrid\Mail\Mail;

class ECPayController extends AbstractController
{

    /**
     * @Route("/prepare", name="prepare.info")
     */
    function prepare()
    {
        return $this->render('ecpay/prepare.html.twig',array(
            'carts' => $_SESSION['cart']
        ));
    }

    /**
     * @Route("/pay", name="ECPay")
     */
    function pay(ProductRepository $productRepository)
    {
        $em = $this->getDoctrine()->getManager();


        $total = 0;
        $name = '';
        $products = $_SESSION['cart'];
        foreach ($products as $item) {
            $total = $total + $item['subTotal'];
            $name = $name.$item['name'].' X'.$item['quantity'].'#';
            $product[]=$item['name'];
            $format[]=$item['format'];
            $quantity[]=$item['quantity'];
            $productId[]=$item['productId'];
            $address = $_POST['address'];

            $change = $productRepository->find($item['productId']);

            $oldFormat =$change->getFormat();
            $oldStock = $change->getStock();
            foreach ($oldFormat as $key => $value)
            {
                if($value==$item['format'])
                {
                    if($oldStock[$key]>$item['quantity'])
                    {
                        $oldStock[$key]=$oldStock[$key]-$item['quantity'];
                    }
                    break;
                }
            }
            /**
             * 避免併發請求及 Critical Section
             */
            try{
                $em->getConnection()->beginTransaction();
                $newProduct = $productRepository->find($item['productId'], LockMode::PESSIMISTIC_WRITE,date("YmdHis"));
                $newProduct->setStock($oldStock);
                $em->persist($newProduct);
                $em->flush();
                $em->getConnection()->commit();
            }
            catch (Exception $e)
            {
                $em->getConnection()->rollback();
                echo "<script>alert('庫存不足，請確認');window.location ='http://localhost:8003/cart';</script>";
                return true;
            }

        }

        $MerchantTradeNo = substr(sha1(uniqid('attempt_')), 0, 20);

        /**
         * 建立訂單 並傳送信件
         */

        $order = new UserOrder();
        $order->setTradeNo($MerchantTradeNo);
        $order->setProduct($product);
        $order->setProductId($productId);
        $order->setFormat($format);
        $order->setQuantity($quantity);
        $order->setStatus(1);
        $order->setUser($this->getUser());
        $order->setAddress($address);
        $em->persist($order);
        $em->flush();

        $email= new Mail();
        $email->setFrom("shopping@example.com", "購物網");
        $email->setSubject('已下單');
        $email->addTo($this->getUser()->getEmail(),'user');
        $email->addContent(
            "text/html", "感謝您的下單"
        );

        $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);

        $sendgrid->send($email);

        unset($_SESSION['cart']);
        return $this->render('ecpay/pay.html.twig',array(
            'TradeNo' => $MerchantTradeNo,
            'date' => date('Y/m/d H:i:s'),
            'total' => $total,
            'name' => $name,
            'address' => $address,
        ));
    }

    /**
     * @Route("/pay/test", name="test.pay")
     */
    function testPay()
    {
        $obj = new ECPay_AllInOne();

        //服務參數
        $obj->ServiceURL  = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';
        $obj->HashKey     = '5294y06JbISpM5x9';
        $obj->HashIV      = 'v77hoKGq4kWxNNIS';
        $obj->MerchantID  = 2000132;
        $MerchantTradeNo = substr(sha1(uniqid('attempt_')), 0, 20);
        //
        $obj->Send['MerchantTradeNo'] = $_POST['MerchantTradeNo'];
        $obj->Send['MerchantTradeDate'] = $_POST['MerchantTradeDate'];
        $obj->Send['PaymentType'] = $_POST['PaymentType'];
        $obj->Send['TotalAmount'] = (int)$_POST['TotalAmount'];
        $obj->Send['TradeDesc'] = '購買清單';
        $obj->Send['ReturnURL'] = $this->generateUrl("callback.ecpay");;
        $obj->Send['ChoosePayment'] = $_POST['ChoosePayment'];
        $obj->Send['ClientBackURL'] = $this->generateUrl("callback.ecpay");;

        //訂單的商品資料
        array_push($obj->Send['Items'], array(
                'Name' => $_POST['ItemName'],
                'Price' => (int)$_POST['TotalAmount'],
                'Currency' => "元",
                'Quantity' => (int)1
            )
        );

        //產生訂單(auto submit至ECPay)
        //$obj->CheckOut();
        $Response = $obj->CheckOut();
        return new Response($Response, Response::HTTP_ACCEPTED, array(
            "Content-Type" => "text/html"
        ));
    }

    /**
     * @Route("callback/ecpay", name="callback.ecpay", methods={"POST"})
     */
    public function callbackecpay(UserOrderRepository $userOrderRepository)
    {
        $arParameters = $_POST;
        foreach ($arParameters as $keys => $value) {
            if ($keys != 'CheckMacValue') {
                if ($keys == 'PaymentType') {
                    $value = str_replace('_CVS', '', $value);
                    $value = str_replace('_BARCODE', '', $value);
                    $value = str_replace('_CreditCard', '', $value);
                }
                if ($keys == 'PeriodType') {
                    $value = str_replace('Y', 'Year', $value);
                    $value = str_replace('M', 'Month', $value);
                    $value = str_replace('D', 'Day', $value);
                }
                $arFeedback[$keys] = $value;
            }
        }

        // 計算出 CheckMacValue
        $CheckMacValue = ECPay_CheckMacValue::generate( $arParameters, '5294y06JbISpM5x9', 'v77hoKGq4kWxNNIS' );

        // 必須要支付成功並且驗證碼正確
        if ( $_POST['RtnCode'] =='1' or $CheckMacValue == $_POST['CheckMacValue'] ){
            //
            // 要處理的程式放在這裡，例如將線上服務啟用、更新訂單資料庫付款資訊等
            //

            $order = $userOrderRepository->findBy(array("TradeNo"=>$arParameters['MerchantTradeNo']));
            $order->setStatus(2);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            $email= new Mail();
            $email->setFrom("shopping@example.com", "購物網");
            $email->setSubject('訂單處理中');
            $email->addTo($this->getUser()->getEmail(),'user');
            $email->addContent(
                "text/html", "您的訂單正在處理中"
            );

            $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);

            $sendgrid->send($email);


        }

        // 接收到資訊回應綠界
        echo '1|OK';
    }


}
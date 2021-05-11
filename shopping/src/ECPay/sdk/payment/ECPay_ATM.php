<?php


namespace App\ECPay\sdk\payment;

class ECPay_ATM extends ECPay_Verification
{
    public  $arPayMentExtend = array(
        'ExpireDate'       => 3,
        'PaymentInfoURL'   => '',
        'ClientRedirectURL'=> '',
    );

    //過濾多餘參數
    function filter_string($arExtend = array(),$InvoiceMark = ''){
        $arExtend = parent::filter_string($arExtend, $InvoiceMark);
        return $arExtend ;
    }
}